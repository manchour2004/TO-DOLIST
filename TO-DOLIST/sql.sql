CREATE DATABASE ToDoList;
USE ToDoList;

CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,           
    prenom VARCHAR(255) NOT NULL,                    
    email VARCHAR(255) NOT NULL,                    
    mdp VARCHAR(255) NOT NULL                    
);
CREATE TABLE Note (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT,
    id_user INT  NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Users(id)
);

CREATE TABLE Tache (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    date_echeance DATE NOT NULL,
    statut BOOLEAN NOT NULL DEFAULT FALSE,
    id_user INT  NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Users(id)
);

CREATE TABLE Projet (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description TEXT,
    statut BOOLEAN NOT NULL DEFAULT FALSE,
    id_user INT  NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Users(id)
);

CREATE TABLE Participe (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_note INT NULL,
    id_tache INT NULL,
    id_projet INT NULL,
    FOREIGN KEY (id_note) REFERENCES Note(id),
    FOREIGN KEY (id_tache) REFERENCES Tache(id),
    FOREIGN KEY (id_projet) REFERENCES Projet(id),
    id_user INT  NOT NULL,
    FOREIGN KEY (id_user) REFERENCES Users(id) 
);