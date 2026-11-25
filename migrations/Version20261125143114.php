<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261125143114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tutorial_tag (tutorial_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_AF0341CF89366B7B (tutorial_id), INDEX IDX_AF0341CFBAD26311 (tag_id), PRIMARY KEY (tutorial_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE tutorial_tag ADD CONSTRAINT FK_AF0341CF89366B7B FOREIGN KEY (tutorial_id) REFERENCES tutorial (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial_tag ADD CONSTRAINT FK_AF0341CFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutorial DROP FOREIGN KEY `FK_C66BFFE98D7B4FB4`');
        $this->addSql('DROP INDEX IDX_C66BFFE98D7B4FB4 ON tutorial');
        $this->addSql('ALTER TABLE tutorial DROP tags_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tutorial_tag DROP FOREIGN KEY FK_AF0341CF89366B7B');
        $this->addSql('ALTER TABLE tutorial_tag DROP FOREIGN KEY FK_AF0341CFBAD26311');
        $this->addSql('DROP TABLE tutorial_tag');
        $this->addSql('ALTER TABLE tutorial ADD tags_id INT NOT NULL');
        $this->addSql('ALTER TABLE tutorial ADD CONSTRAINT `FK_C66BFFE98D7B4FB4` FOREIGN KEY (tags_id) REFERENCES tag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C66BFFE98D7B4FB4 ON tutorial (tags_id)');
    }
}
