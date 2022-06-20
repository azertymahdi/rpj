/**
 * Tuyêt Trâm DANG NGOC (dntt) - 2001
 * Serveur TCP pour multiple clients
 */
package reseau;
import java.net.* ;
import java.nio.CharBuffer;
import java.sql.SQLException;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.logging.FileHandler;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import bdd.SQLDataBase;

import java.io.* ;


/**
 * Classe correspondant au serveur TCP
 * @author Thomas
 *
 */
public class ServeurTCP {
	// utilise pour le log
	public static DateTimeFormatter dateTimeFormatter = DateTimeFormatter.ofPattern("dd/MM/yy HH:mm:ss");
	public static DateTimeFormatter shortTimeFormatter = DateTimeFormatter.ofPattern("dd_HH_mm");
	
	//port d'ecoute par defaut
	private static final int DEFAULT_PORT=9999;
	
	/**
	 * renvoie l'heure actuelle sous la forme dd/MM/yy HH:mm:ss
	 * @return l'heure actuelle sous la forme dd/MM/yy HH:mm:ss
	 */
	public static String  getTime() {
		LocalDateTime dateTime = LocalDateTime.now();
        return dateTimeFormatter.format(dateTime);
	}
	
	
    public static void main (String argv []) {
    	int port=DEFAULT_PORT;
    	// si on donne le port en argument
    	if (argv.length>0) {
    		port = Integer.parseInt(argv[0]);
    	}
    	
    	
        ServerSocket serverSocket = null ;
        boolean listening = true ;
        LocalDateTime dateTime = LocalDateTime.now();
        
        String time= dateTimeFormatter.format(dateTime);
        String shortTime= shortTimeFormatter.format(dateTime);
        Logger logger= Logger.getLogger(time);
        FileHandler fileHandler=null;
        
        // initialisation du logger
        try {
			fileHandler = new FileHandler("ServeurTCP2_" + shortTime);
			logger.addHandler(fileHandler);
		} catch (SecurityException e1) {
			e1.printStackTrace();
		} catch (IOException e1) {
			e1.printStackTrace();
		}
        logger.log(Level.INFO, time+ ": Serveur launched");
        
        
        try {
            serverSocket = new ServerSocket (port) ;
        } 
        catch (IOException e) {
            System.err.println ("Je ne peux pas ecouter sur le port " + port) ;
            logger.log(Level.WARNING, getTime() +": Je ne peux pas ecouter sur le port " + port +
            		"\nVérifier que le programme n'est pas déjà lancé sur ce port ou qu'un autre programme utilise ce port"+e.getMessage());
           
            e.printStackTrace();
            System.exit (-1) ;
        }
        try {
        while (listening)
            new ThreadServeur (serverSocket.accept(),logger).start () ;

        	serverSocket.close () ;
        }catch(Exception e) {
        	logger.log(Level.WARNING, ServeurTCP.getTime() +": Exception: "+ e.getMessage());
        	e.printStackTrace();
        	
        }
        fileHandler.close();
    }
}
/**
 * 
 * @author Thomas
 *
 */
class ThreadServeur extends Thread{
	private Socket clientSocket ;
	private Logger logger;
	private int state=-1;
	private static final int DEFAULT_STATE=1;
	private static final int IDSELECT_STATE=2;
	private static final int DELETE_STATE=3;
	private static final int END_STATE=4;
	private PrintWriter flux_sortie=null;
	private SQLDataBase sdb;
	private String requestedId;
	private String user;

    public ThreadServeur (Socket clientSocket,Logger logger) {
        super ("ThreadServeur") ;
        this.logger=logger;
        logger.log(Level.INFO, ServeurTCP.getTime() +": Connexion with:"+ clientSocket.toString());
        sdb=new SQLDataBase();
        this.clientSocket = clientSocket ;
        
    }
    private void stopMessage() {
    	flux_sortie.println("stop");
    }
    /**
     * Renvoie une string avec des informations pouvant etre utile pour le debug
     * @return string pour debug
     */
    public String debugInfo() {
    	String debug="";
    	debug+= "state=" +state  +" "+ clientSocket.toString()
    	+ " connexion database: " +sdb.getInfoCon();
    	return debug;
    }
    /**
     * A partir d'un charBuffer, recupere son contenu et le renvoie sous forme de String
     * @param charBuffer
     * @return contenu du Buffer
     */
    private String getBufferString(CharBuffer charBuffer) {
    	int bufferSize= charBuffer.capacity() -charBuffer.remaining(); // pas de fonction pour la taille du buffer???
    	char[] chars = charBuffer.array();
    	return new String(chars,0,bufferSize);
    }
    public void run () {
    	try {
    		// initialisation des entrees sorties
	    	 flux_sortie = new PrintWriter(clientSocket.getOutputStream(),
	    			 true);
	    	 BufferedReader flux_entree = new BufferedReader (
	    			 	new InputStreamReader (clientSocket.getInputStream ()));
	    	
	    	 System.out.println(clientSocket.toString()); //info connexion
	    	 state= DEFAULT_STATE;
	    	 String input,output;
	    	 int buffer_size=100; //taille du buffer par défaut
	    	 CharBuffer charBuffer = CharBuffer.allocate(buffer_size);
	    	 //on commence par l'indetification 
	    	 //(tout dialogue commence par une identification)
	    	 flux_entree.read(charBuffer);
	    	 user= getBufferString(charBuffer);
	    	 logger.log(Level.INFO, ServeurTCP.getTime() +": user name=" + user);
	    	 System.out.println(user);
	    	 charBuffer.clear();
	    	 
	    	 // boucle principale
	    	 while (flux_entree.read(charBuffer)!=-1) { // lecture du message du client
	    		 
	    		 // récuperation du message
	    		 input= getBufferString(charBuffer);
	    		 while (flux_entree.ready()) { //si l'on a pas tout lu en une seule fois (le message et plus grand que le buffer)
	    			 charBuffer.clear();
	    			 flux_entree.read(charBuffer);
	    			 input+= getBufferString(charBuffer);
		    	 }
		    	
	    		 System.out.println("Message received");
	    		 System.out.println("Message:"+input);
	    		 
	    		 //traitement en fonction du message reçu
	    		 if (input.equals("end")) {
	    			 flux_sortie.print("end");
	    			 break;
	    		 }
	    		 else if (input.equals("debug")) {
	    			flux_sortie.print(debugInfo());
	    		 }
	    		 else if (input.equals("cancel")) {
	    			 state=DEFAULT_STATE;
	    			 flux_sortie.print("Server reset to default state");
	    		 }
	    		 else if (state== DEFAULT_STATE && input.equals("select_compte")) {
	    			 state=IDSELECT_STATE;
	    			 flux_sortie.print("Enter_Id");
	    		 }
	    		 else if (state== IDSELECT_STATE) { 
	    			 // on vérifie que le message envoyé est un nombre en utilisant REGEX (on aurait pu faire plus simplement)
	    			 // on fait cela meme si le client s'occupe deja de ca
	    			 // au cas ou un utilisateur malveillant utiliserait par exemple netcat pour envoyer un message
	    			 // qui n'est pas un nombre
	    			 try {
	    				requestedId= input;
	    				String pattern = "[^0-9]+";
	    				Pattern pat= Pattern.compile(pattern);
	    				Matcher m = pat.matcher(input);
	    				if (!m.find()) {
							String compte= sdb.getCompte(input);
							flux_sortie.print(compte);
							state=DELETE_STATE;
						}
	    				else {
	    					flux_sortie.print("ERROR: WRONG ID (MUST BE A NUMBER)");
	    				}
					} catch (SQLException e) {
						// erreur dans la requete sql
						// l'id selectionne n'existe probablement pas
						flux_sortie.print("ERROR DURING SQL QUERRY");
							logger.log(Level.WARNING, ServeurTCP.getTime() +": SQLException: "+ e.getErrorCode()
								+" : "+ e.getMessage());
						e.printStackTrace();
					}
	    		 }
	    		 else if (state== DELETE_STATE &&input.equals("delete")) {
	    			 // demande d'effacer le compte
	    			 // montre tout ce qui va être efface
	    			 try {
	    				String message = "ARE YOU SURE? It will also delete:\n"+sdb.getPersonnages(requestedId)+
	    						'\n'+sdb.getEquipement(requestedId);
	    				flux_sortie.print(message);
	    				state=END_STATE;
					} catch (SQLException e) {
						flux_sortie.print("ERROR DURING SELECT SQL QUERRY");
						logger.log(Level.WARNING, ServeurTCP.getTime() +": SQLException: "+ e.getErrorCode()
								+" : "+ e.getMessage());
						e.printStackTrace();
					}
	    		 }
	    		 else if (state== END_STATE &&input.equals("confirm")) {
	    			 // effacer tout ce qui est possede par le compte + le compte
	    			 try {
	    				int result=sdb.deleteCompte(requestedId);
	    				state=DEFAULT_STATE;
	    				flux_sortie.print("Succes! Number of entities destroyed: "+result);
					} catch (SQLException e) {
						flux_sortie.print("ERROR DURING DELETE SQL QUERRY");
						e.printStackTrace();
						logger.log(Level.WARNING, ServeurTCP.getTime() +": SQLException: "+ e.getErrorCode()
								+" : "+ e.getMessage());
					}
	    		 }
	    		 else {
	    			 flux_sortie.print("command not found");
	    		 }
	    		 
	    		
	    		 flux_sortie.flush();// envoi du message (on utilise pas println())
	    		 charBuffer = charBuffer.clear();
	    	 }
	    	 // fin de la communication
	    	 System.out.println("END OF COMMUNICATION");
	    	 //fermeture de la socket + des buffer
	    	 flux_sortie.close () ;
	         flux_entree.close () ;
	         clientSocket.close () ;
	         logger.log(Level.INFO, ServeurTCP.getTime() +": user " + user+ " disconected");
	    }
    	catch (IOException e) { 
    		e.printStackTrace(); 
	    }
    }
  
}




