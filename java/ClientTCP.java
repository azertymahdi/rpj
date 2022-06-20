
package reseau;
import java.io.IOException ;
import java.io.BufferedReader ;
import java.io.InputStreamReader ;
import java.io.PrintWriter ;
import java.io.IOException ;
import java.net.Socket ;
import java.net.UnknownHostException ;
import java.nio.CharBuffer;
import java.util.regex.Matcher;
import java.util.regex.Pattern;
/**
 * Client TCP du projet
 * @author Thomas
 *
 */
public class ClientTCP {
	//nom que le client envoie au serveur
	private static final String CLIENT_NAME= "/!\\ Don't forget to get a real name /!\\";
	private static final int NUMBER_OF_TICKS=50;
	private static final int DEFAULT_PORT=9999;
	//adresse ip par défaut
	private static final String DEFAULT_IP="127.0.0.1";
    public static void main (String argv []) throws IOException {
    	// récupération de l'ipServeur + port
    	String adr = DEFAULT_IP;
    	Boolean hasAnIp=false;
    	Boolean hasAPort=false;
    	int port=DEFAULT_PORT;
    	if (argv.length>=2) {
    		if (argv[0].equals("-ip")) {
    			// test si la chaine est une adresse ip avec un regex
    			String pattern = "(\\b25[0-5]|\\b2[0-4][0-9]|\\b[01]?[0-9][0-9]?)(\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}";
				Pattern pat= Pattern.compile(pattern);
				Matcher m = pat.matcher(argv[1]);
				if (m.matches()) {
					adr=argv[1];
					hasAnIp=true;
				}
				else {
					System.err.println("parameter is not an ip adress");
					System.exit(1);
				}
    		}
    		else if(argv[0].equals("-n")) { // nom DNS
    			adr=argv[1];
    			hasAnIp=true;
    		}
    		if (argv.length>=4 && argv[2].equals("-p")) {
    			port=Integer.parseInt(argv[3]);
    			hasAPort=true;
    		}
    	}
    	
    	if (!hasAnIp) {
    		System.out.println("no ip adress entered, using default ip adress: "+ DEFAULT_IP);
    	}
    	if (!hasAPort) {
    		System.out.println("no port entered, using port: "+DEFAULT_PORT);
    	}
    	
        Socket socket = null ;
        PrintWriter resOut = null ;
        BufferedReader resIn = null ;
        String inputString ;

        try {
            socket = new Socket (adr,port) ;
            resOut = new PrintWriter (socket.getOutputStream (), true) ;
            resIn = new BufferedReader (new InputStreamReader (
                                        socket.getInputStream ())) ;
        } 
        catch (UnknownHostException e) {
            System.err.println ("Connexion impossible") ;
            e.printStackTrace();
            System.exit (1) ;
        } 

        // L'entree standard
        BufferedReader stdin = new BufferedReader ( new InputStreamReader ( System.in)) ;
        int buffer_size=8000;
        CharBuffer charBuffer = CharBuffer.allocate(buffer_size);
  
        resOut.println ("I'm "+ CLIENT_NAME);
        System.out.println("connected to: "+adr);
        ServerHandlerThread shThread= new ServerHandlerThread(socket,charBuffer,resIn); // pour récupérer le flux venant du serveur
        shThread.start();
       
        Boolean nextMessage;
        Boolean nextMessageIsInt=false;
        String mesServeur;
        
        mainLoop: 
        do {
        	inputString="";
        	nextMessage=false;
        	// on lit ce que l'utilisateur a tape sur l'entree standard
        	mesServeur="";
        	inputString= stdin.readLine();
        	if (inputString.equals("end")) {
        		break;
        	}
        	else if (!nextMessageIsInt || isInt(inputString)) {
        		// et on l'envoie au serveur
        		resOut.print(inputString) ;
    			resOut.flush();
    			System.out.println("sent to server, waiting for an answer");
			}
        	else{
        		System.err.println(inputString+" is not a number");
        		Boolean correctInput=false;
        		while(!correctInput) {
        			inputString= stdin.readLine();
	        		if (inputString.equals("end")) {
	            		break;
	            	}
	        		else if (isInt(inputString)) {
	        			correctInput=true;
	        			resOut.print(inputString) ;
	        			resOut.flush();
	        		}
	        		else{
	        			System.err.println(inputString+" is not a number");
	        		}
	        		
        		}
        	}
			
			
			// on lit ce qu'a envoye le serveur
			
			
			Boolean stop=true;
			if (!inputString.equals("")) do  {
				//teste si le serveur répond en moins de NUMBER_OF_TICKS*50 ms
				//Si il ne répond pas, on propose de terminer le programme 
				for (int nbrOfTick=0;nbrOfTick<NUMBER_OF_TICKS;nbrOfTick++) {
					try {
						Thread.sleep(20);
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
					if (shThread.hasRead()) { //on a reçu un message 
						nextMessage=true;
						mesServeur= shThread.getMessage();
						
						if (mesServeur.equals("Enter_Id")) {
							nextMessageIsInt=true;
						}
						else {
							nextMessageIsInt=false;
						}
						stop=true;
						break;
					}
				}
				if (!nextMessage) { // on a pas de message
					System.out.println("No answer from serveur wait or quit? (wait=w else quit)");
					String quit= stdin.readLine();
					if (!quit.equals("w")) {
						System.out.println("quitting");
						stop=true;
						break mainLoop; // un jour j'apprendrais à coder...
					}
					else {
						stop=false;
					}
				}
			}while (!stop);
			
			
			
			System.out.println(mesServeur);
			charBuffer.clear();
			if(inputString.startsWith("end")) {
		        break;
			}
        } while (true) ;
        
        shThread.end();  
	    try {
			Thread.sleep(200);
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	    resOut.close () ;
	    resIn.close () ;
        stdin.close () ;
        socket.close () ;
        System.out.println("program terminated");
      
    }
    public static Boolean isInt(String string) {
    	String pattern = "[^0-9]+";
		Pattern pat= Pattern.compile(pattern);
		Matcher m = pat.matcher(string);
		return !string.equals("")&&!m.find();
    }
    protected static String getBufferString(CharBuffer charBuffer) {
    	int bufferSize= charBuffer.capacity() -charBuffer.remaining(); // pas de fonction pour la taille du buffer???
    	char[] chars = charBuffer.array();
    	return new String(chars,0,bufferSize);
    }
}
/**
 * Classe permettant indirectement de mesurer le temps de reponse du serveur
 * thread dont le but est de recuperer le message envoye par le serveur
 * il est possible de savoir si un message est recupere avec hasread() et de recuperer le message
 * avec getMessage()
 * @author Thomas
 *
 */

class ServerHandlerThread extends Thread {
	private Socket socket;
	private CharBuffer charBuffer;
	private BufferedReader flux_entree;
	private int hasRead=0;
	private Boolean isConnected=true;
	public ServerHandlerThread(Socket socket,CharBuffer charBuffer, BufferedReader flux_entree) {
		this.socket=socket;
		this.charBuffer=charBuffer;
		this.flux_entree=flux_entree;
	}
	
	
	public void run() {
		int i=0;
		while (isConnected) {
			try {
				
				sleep(10);
				i++;
				flux_entree.read(charBuffer);
				hasRead++;
				
			} catch (Exception e) {
				// TODO Auto-generated catch block
				if (isConnected) {
					e.printStackTrace();
					System.exit(1);
				}
			}
			
		}
	}
	/**
	 * termine le thread
	 */
	public void end(){
		
		isConnected=false;
		
	}
	/**
	 * indique si un message a ete recu
	 * @return
	 */
	public Boolean hasRead() {
		
		return hasRead>0;
	}
	/**
	 * renvoie le contenu du message
	 * @return le message
	 */
	public String getMessage() {
		hasRead--;
		return ClientTCP.getBufferString(charBuffer);
	}
	
}



