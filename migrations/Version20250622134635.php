<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250622134635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, model VARCHAR(45) NOT NULL, registration VARCHAR(255) NOT NULL, color VARCHAR(255) DEFAULT NULL, registration_date DATE NOT NULL, fuel VARCHAR(255) NOT NULL, INDEX IDX_773DE69DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE carshare (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, car_id INT NOT NULL, departure_date DATE NOT NULL, departure_hour TIME NOT NULL, departure_location VARCHAR(45) NOT NULL, arrival_date DATE NOT NULL, arrival_hour TIME NOT NULL, arrival_location VARCHAR(45) NOT NULL, available_seats SMALLINT NOT NULL, price DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, smoking_allowance TINYINT(1) NOT NULL, animal_allowance TINYINT(1) NOT NULL, pref LONGTEXT DEFAULT NULL COMMENT '(DC2Type:simple_array)', INDEX IDX_7949F9EDA76ED395 (user_id), INDEX IDX_7949F9EDC3C6F69F (car_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) DEFAULT NULL, roles JSON NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone_nb VARCHAR(45) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, credit_balance INT NOT NULL, birth_date DATE DEFAULT NULL, picture LONGBLOB DEFAULT NULL, is_verified TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_PSEUDO (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_carshare (user_id INT NOT NULL, carshare_id INT NOT NULL, INDEX IDX_99C41DEDA76ED395 (user_id), INDEX IDX_99C41DEDD05257A (carshare_id), PRIMARY KEY(user_id, carshare_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD CONSTRAINT FK_773DE69DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE carshare ADD CONSTRAINT FK_7949F9EDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE carshare ADD CONSTRAINT FK_7949F9EDC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_carshare ADD CONSTRAINT FK_99C41DEDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_carshare ADD CONSTRAINT FK_99C41DEDD05257A FOREIGN KEY (carshare_id) REFERENCES carshare (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE carshare DROP FOREIGN KEY FK_7949F9EDA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE carshare DROP FOREIGN KEY FK_7949F9EDC3C6F69F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_carshare DROP FOREIGN KEY FK_99C41DEDA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_carshare DROP FOREIGN KEY FK_99C41DEDD05257A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE car
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE carshare
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE client
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_carshare
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
