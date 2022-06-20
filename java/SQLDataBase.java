package bdd;
import java.sql.*;
import java.util.ArrayList;
public class SQLDataBase {
	private Connection con;
	public static String INFO_CO1 ="jdbc:postgresql://postgresql-lapagedethomas.alwaysdata.net/lapagedethomas_bddl3s5";
	public static String INFO_CO2="lapagedethomas_thomas";
	public static String INFO_CO3="FvAJMP6K79RW8WG";
	public SQLDataBase() {
		try {
			
			//con = DriverManager.getConnection("jdbc:postgresql://10.40.128.23:5432/db21l3i_e_tsagot","y21l3i_e_tsagot", "A123456*");
			
			con= DriverManager.getConnection(INFO_CO1,INFO_CO2,INFO_CO3);
			System.out.println(con.toString());
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	public String getInfoCon() {
		String infoCon="";
		infoCon+=con.toString();
		return infoCon;
	}
	
	public String getCompte (String idCompte) throws SQLException{
		String querry = "SELECT * FROM Compte WHERE idCompte=?;";
		PreparedStatement pst=con.prepareStatement(querry);
		pst.setString(1,idCompte);
		ResultSet rs = pst.executeQuery();
		rs.next();
		String nomC = rs.getString("nomC");
		String email= rs.getString("eMail");
		return nomC +" "+email;
	}
	public String getPersonnages(String idCompte) throws SQLException{
		String querry="SELECT * FROM Compte c, Personnage p WHERE c.idCompte=? AND p.idCompte=?";
		PreparedStatement pst=con.prepareStatement(querry);
		pst.setString(1,idCompte);
		pst.setString(2,idCompte);
		ResultSet rs = pst.executeQuery();
		String perso="";
		while (rs.next()) {
			perso += "idPer= "+rs.getInt("idPer");
			perso += " nomPer= "+rs.getString("nomPer");
			perso += " niveau= "+rs.getInt("niveau");
			perso += " experience= "+rs.getBigDecimal("experience");
			perso += " vie de base="+rs.getInt("vieDeBase");
			perso += "\n";
		}
		return perso;
	}
	public String getEquipement(String idCompte) throws SQLException{
		ArrayList<ResultSet> rss= new ArrayList<ResultSet>(2);
		String perso="";
		rss.add(getEqupementByType('a',idCompte));
		rss.add(getEqupementByType('d',idCompte));
		char type='a'; // nulAchier
		for (ResultSet rs : rss) {
			while (rs.next()) {
				String idEquipement= rs.getString("idEquipement");
				perso += " nomC= "+rs.getString("nomC");
				perso += " niveau= "+rs.getInt("niveau");
				perso += " prix= "+rs.getInt("prix");
				ResultSet rsMod= getEquiModByType(type,idEquipement);
				while (rsMod.next()) {
					perso += " mod= " +rsMod.getString("type");
					perso += " tier= " +rsMod.getInt("tier");
					perso += " valeur= " +rsMod.getString("valeur");
				}
			
				perso += "\n"; 
			}
		}
		return perso;
	}
	private ResultSet getEqupementByType(char type, String idCompte) throws SQLException {
		String query="";
		switch (type) {
		case('a'):
			query="SELECT * FROM  Arme a, Equipement e WHERE a.idArme=e.idEquipement AND e.idCompte=?";
		break;
		case('d'):
			query="SELECT * FROM  Armure d, Equipement e WHERE d.idArmure=e.idEquipement AND e.idCompte=?;";
		break;
		default:
			
		}
		PreparedStatement pst=con.prepareStatement(query);
		pst.setString(1,idCompte);
		return pst.executeQuery();
	}
	private ResultSet getEquiModByType(char type, String idEquipement) throws SQLException {
		String query="";
		int id = Integer.parseInt(idEquipement);
		switch (type) {
		case('a'):
			query="SELECT * FROM  Modificateur m, modifiArmure ma WHERE ma.idArmure=? AND ma.idMod=m.idMod;";
		break;
		case('d'):
			query="SELECT * FROM  Modificateur m, modifiArme ma WHERE ma.idArme=? AND ma.idMod=m.idMod;";
		break;
		default:
			
		}
		PreparedStatement pst=con.prepareStatement(query);
		pst.setInt(1,id);
		return pst.executeQuery();
	}
	public int deleteCompte(String idCompte) throws SQLException {
		String query = "DELETE FROM Compte WHERE idCompte= ? ;";
		PreparedStatement pst=con.prepareStatement(query);
		pst.setString(1,idCompte);
		return pst.executeUpdate();
		
	}

}
