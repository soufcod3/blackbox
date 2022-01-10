<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102082014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seriesWatchlist (user_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_CBD9D71FA76ED395 (user_id), INDEX IDX_CBD9D71F5278319C (series_id), PRIMARY KEY(user_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moviesWatchlist (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_7046E028A76ED395 (user_id), INDEX IDX_7046E0288F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE seriesWatchlist ADD CONSTRAINT FK_CBD9D71FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seriesWatchlist ADD CONSTRAINT FK_CBD9D71F5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE moviesWatchlist ADD CONSTRAINT FK_7046E028A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE moviesWatchlist ADD CONSTRAINT FK_7046E0288F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE seriesWatchlist');
        $this->addSql('DROP TABLE moviesWatchlist');
    }
}
