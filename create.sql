CREATE TABLE Guilde(
	nomGuilde VARCHAR(30) NOT NULL,
	embleme BYTEA ,
	dateCreationGuilde DATE,
	chef CHAR(10),
	CONSTRAINT Guilde_pk PRIMARY KEY (nomGuilde)

);

CREATE TABLE Compte(
	idCompte CHAR(10) NOT NULL,
	nomC VARCHAR(30),
	dateCreation DATE,
	serveur VARCHAR(10),
	recherchePrec XML,
	eMail VARCHAR(50),
	mdp CHAR(128),
	solde INT check(solde > 0) ,
	nomGuilde VARCHAR(30),
	CONSTRAINT Compte_pk PRIMARY KEY (idCompte),
	CONSTRAINT  Compte_fk FOREIGN KEY (nomGuilde) REFERENCES Guilde (nomGuilde)
);


CREATE TABLE Personnage (
	idPer INT,
	nomPer VARCHAR(30),
	niveau INT  check(niveau BETWEEN 1 and 100),
	experience bigint,
	vieDebase INT,
	idCompte CHAR(10) NOT NULL,
	CONSTRAINT Personnage_pk PRIMARY KEY (idPer),
	CONSTRAINT Personnage_fk FOREIGN KEY (idCompte) REFERENCES Compte (idCompte)
	ON UPDATE CASCADE
	ON DELETE CASCADE
 
);

CREATE TABLE Classement (
	idClassement INT,
	position INT  ,
	saison INT    ,
	dateDebutS DATE,
	CONSTRAINT Classement_fk PRIMARY KEY (idClassement)
	
);

 

CREATE TABLE Equipement(
	idEquipement INT NOT NULL,
	nomC VARCHAR(50),
	niveau INT check(niveau BETWEEN 1 and 100) ,
	apparence BYTEA ,
	idCompte CHAR(10) NOT NULL,
	prix INT ,
	idPer INT,
	CONSTRAINT Equipement_pk PRIMARY KEY (idEquipement),
	CONSTRAINT Equipement_id_fk FOREIGN KEY (idCompte) REFERENCES  Compte(idCompte)
	ON UPDATE CASCADE
	ON DELETE CASCADE,
	CONSTRAINT Equipement_idPer_fk FOREIGN KEY (idPer) REFERENCES  Personnage(idPer)
	ON UPDATE CASCADE
	ON DELETE CASCADE
	

);


CREATE TABLE EstClasse(
	idPer INT,
	idClassement INT,
	CONSTRAINT EstClasse_pk FOREIGN KEY (idPer) REFERENCES Personnage(idPer)
	ON UPDATE CASCADE
	ON DELETE CASCADE,
	CONSTRAINT EstClasse_position_fk FOREIGN KEY (idClassement) REFERENCES Classement(idClassement)
	ON UPDATE CASCADE
	ON DELETE CASCADE,
	PRIMARY KEY(idPer, idClassement)
	
	
 
);

CREATE TABLE Arme (
	idArme INT,
	attaque INT ,
	critChance FLOAT check(critChance >0) ,
	degatCrit FLOAT check(degatCrit > 1),
	vitesseAttaque FLOAT check(vitesseAttaque > 0),
	CONSTRAINT Arme_pk PRIMARY KEY (idArme),
	CONSTRAINT Arme_idArme_fk FOREIGN KEY (idArme) REFERENCES Equipement(idEquipement)
	ON UPDATE CASCADE
	ON DELETE CASCADE


);

CREATE TABLE Modificateur(
	idMod VARCHAR(55),
	tier INT check(tier > 0),
	niveauMod INT,
	type VARCHAR(50),
	valeur VARCHAR(30),
	CONSTRAINT Modificateur_pk PRIMARY KEY (idMod)


);


CREATE TABLE Armure (
	idArmure INT,
	defence INT ,
	CONSTRAINT Armure_pk PRIMARY KEY (idArmure),
	CONSTRAINT Armure_idArmure_fk FOREIGN KEY (idArmure) REFERENCES  Equipement(idEquipement)
	ON UPDATE CASCADE
	ON DELETE CASCADE

);






CREATE TABLE ModifiArme(
	idArme INT ,
	idMod VARCHAR(55),

	CONSTRAINT ModifiArme_idArme_fk FOREIGN KEY (idArme) REFERENCES Arme(idArme)
	ON UPDATE CASCADE
	ON DELETE CASCADE,
	CONSTRAINT ModifiArme_modificateur_fk FOREIGN KEY (idMod) REFERENCES Modificateur (idMod)
	ON UPDATE CASCADE
	ON DELETE CASCADE,
	PRIMARY KEY (idArme,idMod)
	

);

CREATE TABLE ModifiArmure(
	idArmure INT,
	idMod VARCHAR(55),

	CONSTRAINT ModifiArmure_idArmure_fk FOREIGN KEY (idArmure) REFERENCES Equipement (idEquipement)
	ON UPDATE CASCADE
	ON DELETE CASCADE,
	CONSTRAINT ModifiArmure_modificateur_fk FOREIGN KEY (idMod) REFERENCES Modificateur (idMod)
	ON UPDATE CASCADE
	ON DELETE CASCADE,
	PRIMARY KEY (idArmure,idMod)
	
);




