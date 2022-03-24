<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220323200436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fortune DROP FOREIGN KEY FK_7F7BE60F67B3B43D');
        $this->addSql('DROP INDEX IDX_7F7BE60F67B3B43D ON fortune');
        $this->addSql('ALTER TABLE fortune DROP users_id');
        $this->addSql('ALTER TABLE users ADD fortune_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9A2EE7EAC FOREIGN KEY (fortune_id) REFERENCES fortune (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9A2EE7EAC ON users (fortune_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fortune ADD users_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fortune ADD CONSTRAINT FK_7F7BE60F67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_7F7BE60F67B3B43D ON fortune (users_id)');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9A2EE7EAC');
        $this->addSql('DROP INDEX IDX_1483A5E9A2EE7EAC ON users');
        $this->addSql('ALTER TABLE users DROP fortune_id');
    }
}
