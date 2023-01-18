<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114060542 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actuacion (id INT AUTO_INCREMENT NOT NULL, tp_actuacion_id INT NOT NULL, expediente_id VARCHAR(15) NOT NULL, nombre VARCHAR(255) NOT NULL, fecha_alta DATETIME NOT NULL, descripcion LONGTEXT DEFAULT NULL, direccion LONGTEXT DEFAULT NULL, INDEX IDX_321583D3704E4A4E (tp_actuacion_id), INDEX IDX_321583D34BF37E4E (expediente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, tipo ENUM(\'P\', \'PT\', \'O\'), UNIQUE INDEX UNIQ_F41C9B25F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrario (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, tipo ENUM(\'A\', \'N\', \'J\'), UNIQUE INDEX UNIQ_FFD95067F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documento (id INT AUTO_INCREMENT NOT NULL, actuacion_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_B6B12EC794FD6108 (actuacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediente (id VARCHAR(15) NOT NULL, procurador_id INT DEFAULT NULL, juzgado_id INT DEFAULT NULL, tp_procedimiento_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, fecha_alta DATETIME NOT NULL, estado ENUM(\'A\', \'P\', \'C\'), num_ref_exped VARCHAR(50) DEFAULT NULL, num_autos VARCHAR(100) DEFAULT NULL, INDEX IDX_D59CA4132DBC7623 (procurador_id), INDEX IDX_D59CA413B9A57363 (juzgado_id), INDEX IDX_D59CA413147ED7AF (tp_procedimiento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediente_juez (expediente_id VARCHAR(15) NOT NULL, juez_id INT NOT NULL, INDEX IDX_3F9506D54BF37E4E (expediente_id), INDEX IDX_3F9506D52515F440 (juez_id), PRIMARY KEY(expediente_id, juez_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediente_contrario (expediente_id VARCHAR(15) NOT NULL, contrario_id INT NOT NULL, INDEX IDX_4640952D4BF37E4E (expediente_id), INDEX IDX_4640952D6E6D2F4E (contrario_id), PRIMARY KEY(expediente_id, contrario_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediente_cliente (expediente_id VARCHAR(15) NOT NULL, cliente_id INT NOT NULL, INDEX IDX_EA359DFC4BF37E4E (expediente_id), INDEX IDX_EA359DFCDE734E51 (cliente_id), PRIMARY KEY(expediente_id, cliente_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE expediente_user (expediente_id VARCHAR(15) NOT NULL, user_id INT NOT NULL, INDEX IDX_3DB9B59C4BF37E4E (expediente_id), INDEX IDX_3DB9B59CA76ED395 (user_id), PRIMARY KEY(expediente_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juez (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, num_profesion VARCHAR(45) NOT NULL, UNIQUE INDEX UNIQ_8FBF6500F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juzgado (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, direccion LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nota (id INT AUTO_INCREMENT NOT NULL, actuacion_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, INDEX IDX_C8D03E0D94FD6108 (actuacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(150) NOT NULL, dni VARCHAR(15) NOT NULL, email VARCHAR(255) NOT NULL, direccion LONGTEXT DEFAULT NULL, telefono VARCHAR(8) DEFAULT NULL, UNIQUE INDEX UNIQ_51E5B69B7F8F253B (dni), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE procurador (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, num_abogado VARCHAR(45) NOT NULL, UNIQUE INDEX UNIQ_30D459EDF5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, role_name LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', status TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tp_actuacion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tp_procedimiento (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, persona_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, foto VARCHAR(255) DEFAULT NULL, num_abogado VARCHAR(45) DEFAULT NULL, tipo VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actuacion ADD CONSTRAINT FK_321583D3704E4A4E FOREIGN KEY (tp_actuacion_id) REFERENCES tp_actuacion (id)');
        $this->addSql('ALTER TABLE actuacion ADD CONSTRAINT FK_321583D34BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id)');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B25F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE contrario ADD CONSTRAINT FK_FFD95067F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE documento ADD CONSTRAINT FK_B6B12EC794FD6108 FOREIGN KEY (actuacion_id) REFERENCES actuacion (id)');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA4132DBC7623 FOREIGN KEY (procurador_id) REFERENCES procurador (id)');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA413B9A57363 FOREIGN KEY (juzgado_id) REFERENCES juzgado (id)');
        $this->addSql('ALTER TABLE expediente ADD CONSTRAINT FK_D59CA413147ED7AF FOREIGN KEY (tp_procedimiento_id) REFERENCES tp_procedimiento (id)');
        $this->addSql('ALTER TABLE expediente_juez ADD CONSTRAINT FK_3F9506D54BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expediente_juez ADD CONSTRAINT FK_3F9506D52515F440 FOREIGN KEY (juez_id) REFERENCES juez (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expediente_contrario ADD CONSTRAINT FK_4640952D4BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expediente_contrario ADD CONSTRAINT FK_4640952D6E6D2F4E FOREIGN KEY (contrario_id) REFERENCES contrario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expediente_cliente ADD CONSTRAINT FK_EA359DFC4BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expediente_cliente ADD CONSTRAINT FK_EA359DFCDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expediente_user ADD CONSTRAINT FK_3DB9B59C4BF37E4E FOREIGN KEY (expediente_id) REFERENCES expediente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE expediente_user ADD CONSTRAINT FK_3DB9B59CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE juez ADD CONSTRAINT FK_8FBF6500F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE nota ADD CONSTRAINT FK_C8D03E0D94FD6108 FOREIGN KEY (actuacion_id) REFERENCES actuacion (id)');
        $this->addSql('ALTER TABLE procurador ADD CONSTRAINT FK_30D459EDF5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actuacion DROP FOREIGN KEY FK_321583D3704E4A4E');
        $this->addSql('ALTER TABLE actuacion DROP FOREIGN KEY FK_321583D34BF37E4E');
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B25F5F88DB9');
        $this->addSql('ALTER TABLE contrario DROP FOREIGN KEY FK_FFD95067F5F88DB9');
        $this->addSql('ALTER TABLE documento DROP FOREIGN KEY FK_B6B12EC794FD6108');
        $this->addSql('ALTER TABLE expediente DROP FOREIGN KEY FK_D59CA4132DBC7623');
        $this->addSql('ALTER TABLE expediente DROP FOREIGN KEY FK_D59CA413B9A57363');
        $this->addSql('ALTER TABLE expediente DROP FOREIGN KEY FK_D59CA413147ED7AF');
        $this->addSql('ALTER TABLE expediente_juez DROP FOREIGN KEY FK_3F9506D54BF37E4E');
        $this->addSql('ALTER TABLE expediente_juez DROP FOREIGN KEY FK_3F9506D52515F440');
        $this->addSql('ALTER TABLE expediente_contrario DROP FOREIGN KEY FK_4640952D4BF37E4E');
        $this->addSql('ALTER TABLE expediente_contrario DROP FOREIGN KEY FK_4640952D6E6D2F4E');
        $this->addSql('ALTER TABLE expediente_cliente DROP FOREIGN KEY FK_EA359DFC4BF37E4E');
        $this->addSql('ALTER TABLE expediente_cliente DROP FOREIGN KEY FK_EA359DFCDE734E51');
        $this->addSql('ALTER TABLE expediente_user DROP FOREIGN KEY FK_3DB9B59C4BF37E4E');
        $this->addSql('ALTER TABLE expediente_user DROP FOREIGN KEY FK_3DB9B59CA76ED395');
        $this->addSql('ALTER TABLE juez DROP FOREIGN KEY FK_8FBF6500F5F88DB9');
        $this->addSql('ALTER TABLE nota DROP FOREIGN KEY FK_C8D03E0D94FD6108');
        $this->addSql('ALTER TABLE procurador DROP FOREIGN KEY FK_30D459EDF5F88DB9');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5F88DB9');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('DROP TABLE actuacion');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE contrario');
        $this->addSql('DROP TABLE documento');
        $this->addSql('DROP TABLE expediente');
        $this->addSql('DROP TABLE expediente_juez');
        $this->addSql('DROP TABLE expediente_contrario');
        $this->addSql('DROP TABLE expediente_cliente');
        $this->addSql('DROP TABLE expediente_user');
        $this->addSql('DROP TABLE juez');
        $this->addSql('DROP TABLE juzgado');
        $this->addSql('DROP TABLE nota');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE procurador');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE tp_actuacion');
        $this->addSql('DROP TABLE tp_procedimiento');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
