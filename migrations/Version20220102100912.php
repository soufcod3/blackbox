<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102100912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoriteSeries (user_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_10CC3A3A76ED395 (user_id), INDEX IDX_10CC3A35278319C (series_id), PRIMARY KEY(user_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoriteMovies (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_FD022FBEA76ED395 (user_id), INDEX IDX_FD022FBE8F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seenSeries (user_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_27FCAED9A76ED395 (user_id), INDEX IDX_27FCAED95278319C (series_id), PRIMARY KEY(user_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seenMovies (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_DBF242C4A76ED395 (user_id), INDEX IDX_DBF242C48F93B6FC (movie_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoriteSeries ADD CONSTRAINT FK_10CC3A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoriteSeries ADD CONSTRAINT FK_10CC3A35278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoriteMovies ADD CONSTRAINT FK_FD022FBEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoriteMovies ADD CONSTRAINT FK_FD022FBE8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seenSeries ADD CONSTRAINT FK_27FCAED9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seenSeries ADD CONSTRAINT FK_27FCAED95278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seenMovies ADD CONSTRAINT FK_DBF242C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE seenMovies ADD CONSTRAINT FK_DBF242C48F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user_movie');
        $this->addSql('DROP TABLE user_series');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_movie (user_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_FF9C09378F93B6FC (movie_id), INDEX IDX_FF9C0937A76ED395 (user_id), PRIMARY KEY(user_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_series (user_id INT NOT NULL, series_id INT NOT NULL, INDEX IDX_5F421A105278319C (series_id), INDEX IDX_5F421A10A76ED395 (user_id), PRIMARY KEY(user_id, series_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C09378F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_movie ADD CONSTRAINT FK_FF9C0937A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_series ADD CONSTRAINT FK_5F421A105278319C FOREIGN KEY (series_id) REFERENCES series (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_series ADD CONSTRAINT FK_5F421A10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE favoriteSeries');
        $this->addSql('DROP TABLE favoriteMovies');
        $this->addSql('DROP TABLE seenSeries');
        $this->addSql('DROP TABLE seenMovies');
    }
}
