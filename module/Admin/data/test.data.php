<?php
//queries used by tests
return array(
    'posts' => array(
        'create' => 'CREATE TABLE IF NOT EXISTS "posts" (
                      "id" INT NOT NULL AUTO_INCREMENT,
                      "title" VARCHAR(100) NOT NULL,
                      "description" TEXT NOT NULL,
                      "post_created" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
                      PRIMARY KEY (id) ) 
                     ENGINE = InnoDB;',
        'drop' => "DROP TABLE posts;"
    ),
    'comment' => array(
        'create' => 'CREATE TABLE IF NOT EXISTS "comments" (
                      "id" INT NOT NULL AUTO_INCREMENT,
                      "post_id" INT NOT NULL,
                      "description" TEXT NOT NULL,
                      "name" VARCHAR(200) NOT NULL,
                      "email" VARCHAR(250) NOT NULL,
                      "webpage" VARCHAR(200) NOT NULL,
                      "comment_date" TIMESTAMP NULL,
                      CONSTRAINT "fk_comments_post" FOREIGN KEY ("post_id") REFERENCES "posts" ("id") 
                      ON DELETE NO ACTION ON UPDATE NO ACTION) 
                     ENGINE = InnoDB;',
        'drop' =>'DROP TABLE comments;'
    ),
    'users' => array(
        'create' => 'CREATE TABLE IF NOT EXISTS users (
                      "id" INT NOT NULL AUTO_INCREMENT,
                      "username" VARCHAR(200) NOT NULL,
                      "password" VARCHAR(250) NOT NULL,
                      "name" VARCHAR(200) NOT NULL,
                      valid TINYINT NULL,
                      role VARCHAR(20) NULL,
                      PRIMARY KEY (id) )
                     ENGINE = InnoDB;',
        'drop' => 'DROP TABLE users;',
    ),
);