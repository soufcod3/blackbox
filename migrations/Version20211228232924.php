<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228232924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, portrait VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actor_series (actor_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_CD56D29B10DAF24A (actor_id), INDEX IDX_CD56D29B5278319C (series_id), PRIMARY KEY(actor_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actor_movie (actor_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_39DA19FB10DAF24A (actor_id), INDEX IDX_39DA19FB8F93B6FC (movie_id), PRIMARY KEY(actor_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actor_series ADD CONSTRAINT FK_CD56D29B10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actor_series ADD CONSTRAINT FK_CD56D29B5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actor_movie ADD CONSTRAINT FK_39DA19FB10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actor_movie ADD CONSTRAINT FK_39DA19FB8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actor_series DROP FOREIGN KEY FK_CD56D29B10DAF24A');
        $this->addSql('ALTER TABLE actor_movie DROP FOREIGN KEY FK_39DA19FB10DAF24A');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE actor_series');
        $this->addSql('DROP TABLE actor_movie');
    }
}
