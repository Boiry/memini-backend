<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201101145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD memini_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CEF98C2B2 FOREIGN KEY (memini_id) REFERENCES memini (id)');
        $this->addSql('CREATE INDEX IDX_9474526CEF98C2B2 ON comment (memini_id)');
        $this->addSql('ALTER TABLE memini ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE memini ADD CONSTRAINT FK_152005BEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_152005BEA76ED395 ON memini (user_id)');
        $this->addSql('ALTER TABLE tag ADD memini_id INT NOT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783EF98C2B2 FOREIGN KEY (memini_id) REFERENCES memini (id)');
        $this->addSql('CREATE INDEX IDX_389B783EF98C2B2 ON tag (memini_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CEF98C2B2');
        $this->addSql('DROP INDEX IDX_9474526CEF98C2B2 ON comment');
        $this->addSql('ALTER TABLE comment DROP memini_id');
        $this->addSql('ALTER TABLE memini DROP FOREIGN KEY FK_152005BEA76ED395');
        $this->addSql('DROP INDEX IDX_152005BEA76ED395 ON memini');
        $this->addSql('ALTER TABLE memini DROP user_id');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783EF98C2B2');
        $this->addSql('DROP INDEX IDX_389B783EF98C2B2 ON tag');
        $this->addSql('ALTER TABLE tag DROP memini_id');
    }
}
