CREATE DATABASE IF NOT EXISTS Unite_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Unite_db;

CREATE TABLE Professeur (
    ID_Professeur INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Nom VARCHAR(255),
    Prenom VARCHAR(255),
    Email VARCHAR(255),
    Mdp VARCHAR(255),
    DateRegistration DATETIME DEFAULT CURRENT_TIMESTAMP,
    Est_Admin BOOLEAN DEFAULT FALSE,
    Avatar INT DEFAULT 1
);

CREATE TABLE Sujet (
    ID_Sujet INT unsigned AUTO_INCREMENT PRIMARY key ,
    Titre VARCHAR(255) ,
    Descrition TEXT ,
    Date_Ajout DATETIME DEFAULT CURRENT_TIMESTAMP ,
    Est_Personnalise BOOLEAN ,
    Est_Valide BOOLEAN ,
    ID_Professeur INT unsigned ,
    Foreign key (id) References Professors (id)
);

CREATE TABLE Groupe (
    ID_Groupe INT unsigned AUTO_INCREMENT PRIMARY key ,
    ID_Sujet INT unsigned ,
    Foreign key (ID_Sujet) References Sujet (ID_Sujet)
);

CREATE TABLE Etudiant (
    ID_Etudiant INT Unsigned AUTO_INCREMENT PRIMARY Key ,
    Nom VARCHAR(255) ,
    Prenom VARCHAR(255) ,
    Email VARCHAR(255) ,
    Mdp VARCHAR(255) ,
    Filiere_Precedente VARCHAR(255) ,
    DateRegistration DATETIME DEFAULT CURRENT_TIMESTAMP ,
    Dans_Un_Groupe BOOLEAN ,
    Est_Chef BOOLEAN ,
    Sexe VARCHAR(10) ,
    Avatar INT DEFAULT 1,
    ID_Groupe INT Unsigned ,
    Foreign key (ID_Groupe) References Groupe (ID_Groupe)
);

CREATE TABLE Tache (
    ID_Tache INT unsigned AUTO_INCREMENT PRIMARY key ,
    Titre VARCHAR(255) ,
    Date_Debut DATE ,
    Date_Fin DATE ,
    Etat VARCHAR(50) ,
    ID_Etudiant INT unsigned ,
    Foreign key (ID_Etudiant) References Etudiant (ID_Etudiant)
);

CREATE TABLE Note (
    ID_Note INT unsigned AUTO_INCREMENT PRIMARY key ,
    Titre VARCHAR(255) ,
    Contenu TEXT ,
    ID_Tache INT unsigned ,
    Foreign key (ID_Tache) References Tache (ID_Tache)
);

CREATE TABLE Remarque (
    ID_Remarque INT unsigned AUTO_INCREMENT PRIMARY key ,
    Contenu TEXT ,
    Date_Ajout DATE ,
    ID_Sujet INT unsigned ,
    Foreign key (ID_Sujet) References Sujet (ID_Sujet)
);


-- SQL SERVER

IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'Unite_db')
CREATE DATABASE Unite_db COLLATE SQL_Latin1_General_CP1_CI_AS;
GO

USE Unite_db;
GO

-- Tables
CREATE TABLE Professeur (
    ID_Professeur INT IDENTITY(1,1) PRIMARY KEY,
    Nom NVARCHAR(255),
    Prenom NVARCHAR(255),
    Email NVARCHAR(255),
    DateRegistration DATETIME DEFAULT GETDATE(),
    Est_Admin BIT
);

CREATE TABLE Sujet (
    ID_Sujet INT IDENTITY(1,1) PRIMARY KEY,
    Titre NVARCHAR(255),
    Date_Ajout DATE,
    Est_Personnalise BIT,
    Est_Valide BIT,
    ID_Professeur INT,
    FOREIGN KEY (ID_Professeur) REFERENCES Professeur(ID_Professeur)
);

CREATE TABLE Groupe (
    ID_Groupe INT IDENTITY(1,1) PRIMARY KEY,
    ID_Sujet INT,
    FOREIGN KEY (ID_Sujet) REFERENCES Sujet(ID_Sujet)
);

CREATE TABLE Etudiant (
    ID_Etudiant INT IDENTITY(1,1) PRIMARY KEY,
    Nom NVARCHAR(255),
    Prenom NVARCHAR(255),
    Email NVARCHAR(255),
    Filiere_Precedente NVARCHAR(255),
    DateRegistration DATETIME DEFAULT GETDATE(),
    Dans_Un_Groupe BIT,
    Est_Chef BIT,
    Sexe NVARCHAR(10),
    ID_Groupe INT,
    FOREIGN KEY (ID_Groupe) REFERENCES Groupe(ID_Groupe)
);

CREATE TABLE Tache (
    ID_Tache INT IDENTITY(1,1) PRIMARY KEY,
    Titre NVARCHAR(255),
    Date_Debut DATE,
    Date_Fin DATE,
    Etat NVARCHAR(50),
    ID_Etudiant INT,
    FOREIGN KEY (ID_Etudiant) REFERENCES Etudiant(ID_Etudiant)
);

CREATE TABLE Note (
    ID_Note INT IDENTITY(1,1) PRIMARY KEY,
    Titre NVARCHAR(255),
    Contenu NVARCHAR(MAX),
    ID_Tache INT,
    FOREIGN KEY (ID_Tache) REFERENCES Tache(ID_Tache)
);

CREATE TABLE Remarque (
    ID_Remarque INT IDENTITY(1,1) PRIMARY KEY,
    Contenu NVARCHAR(MAX),
    Date_Ajout DATE,
    ID_Sujet INT,
    FOREIGN KEY (ID_Sujet) REFERENCES Sujet(ID_Sujet)
);
