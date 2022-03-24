<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220319213016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE energy (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category VARCHAR(255) NOT NULL, current INT NOT NULL, stamp INT NOT NULL, used INT NOT NULL, INDEX IDX_97117991A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pve (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, battle_start INT NOT NULL, battle_finish INT NOT NULL, boss_id INT NOT NULL, health INT NOT NULL, INDEX IDX_EF0DFAE5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pve_users (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, battle_id INT DEFAULT NULL, created INT NOT NULL, visit INT NOT NULL, health INT NOT NULL, damage INT NOT NULL, INDEX IDX_8A62C4F8A76ED395 (user_id), INDEX IDX_8A62C4F8C9732719 (battle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, session_key VARCHAR(255) NOT NULL, created INT NOT NULL, count INT NOT NULL, friends LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', referrer VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D044D5D4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, sound TINYINT(1) NOT NULL, sound_volume INT NOT NULL, music TINYINT(1) NOT NULL, music_volume INT NOT NULL, UNIQUE INDEX UNIQ_E545A0C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skills (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, sex VARCHAR(255) NOT NULL, skills LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', store LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_D5311670A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, battle_id INT DEFAULT NULL, platform_id INT NOT NULL, real_name VARCHAR(255) NOT NULL, sex VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, room INT NOT NULL, experience BIGINT NOT NULL, battle_rank INT NOT NULL, currency LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at INT NOT NULL, last_time INT NOT NULL, INDEX IDX_1483A5E9C9732719 (battle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE energy ADD CONSTRAINT FK_97117991A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pve ADD CONSTRAINT FK_EF0DFAE5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pve_users ADD CONSTRAINT FK_8A62C4F8A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pve_users ADD CONSTRAINT FK_8A62C4F8C9732719 FOREIGN KEY (battle_id) REFERENCES pve (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D5311670A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C9732719 FOREIGN KEY (battle_id) REFERENCES pve (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pve_users DROP FOREIGN KEY FK_8A62C4F8C9732719');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C9732719');
        $this->addSql('ALTER TABLE energy DROP FOREIGN KEY FK_97117991A76ED395');
        $this->addSql('ALTER TABLE pve DROP FOREIGN KEY FK_EF0DFAE5A76ED395');
        $this->addSql('ALTER TABLE pve_users DROP FOREIGN KEY FK_8A62C4F8A76ED395');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4A76ED395');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('ALTER TABLE skills DROP FOREIGN KEY FK_D5311670A76ED395');
        $this->addSql('DROP TABLE energy');
        $this->addSql('DROP TABLE pve');
        $this->addSql('DROP TABLE pve_users');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE skills');
        $this->addSql('DROP TABLE users');
    }
}
