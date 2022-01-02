<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102101021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE watchlistSeries (user_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_1957736BA76ED395 (user_id), INDEX IDX_1957736B5278319C (series_id), PRIMARY KEY(user_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE watchlistMovies (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_E5599F76A76ED395 (user_id), INDEX IDX_E5599F768F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE watchlistSeries ADD CONSTRAINT FK_1957736BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE watchlistSeries ADD CONSTRAINT FK_1957736B5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE watchlistMovies ADD CONSTRAINT FK_E5599F76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE watchlistMovies ADD CONSTRAINT FK_E5599F768F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE moviesWatchlist');
        $this->addSql('DROP TABLE seriesWatchlist');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE moviesWatchlist (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_7046E0288F93B6FC (movie_id), INDEX IDX_7046E028A76ED395 (user_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE seriesWatchlist (user_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_CBD9D71F5278319C (series_id), INDEX IDX_CBD9D71FA76ED395 (user_id), PRIMARY KEY(user_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE moviesWatchlist ADD CONSTRAINT FK_7046E0288F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE moviesWatchlist ADD CONSTRAINT FK_7046E028A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seriesWatchlist ADD CONSTRAINT FK_CBD9D71F5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seriesWatchlist ADD CONSTRAINT FK_CBD9D71FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE watchlistSeries');
        $this->addSql('DROP TABLE watchlistMovies');
    }
}
