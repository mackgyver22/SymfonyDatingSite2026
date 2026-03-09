<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260309024018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE like_dislike (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_like_dislike (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, like_relation_id INT NOT NULL, INDEX IDX_2D7ADA8BA76ED395 (user_id), INDEX IDX_2D7ADA8B81937FD5 (like_relation_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_like_dislike ADD CONSTRAINT FK_2D7ADA8BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_like_dislike ADD CONSTRAINT FK_2D7ADA8B81937FD5 FOREIGN KEY (like_relation_id) REFERENCES like_dislike (id)');
        $this->addSql('ALTER TABLE user_profile ADD zip_code VARCHAR(255) DEFAULT NULL, ADD city_id INT DEFAULT NULL, ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4058BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE user_profile ADD CONSTRAINT FK_D95AB4055D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('CREATE INDEX IDX_D95AB4058BAC62AF ON user_profile (city_id)');
        $this->addSql('CREATE INDEX IDX_D95AB4055D83CC1 ON user_profile (state_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_like_dislike DROP FOREIGN KEY FK_2D7ADA8BA76ED395');
        $this->addSql('ALTER TABLE user_like_dislike DROP FOREIGN KEY FK_2D7ADA8B81937FD5');
        $this->addSql('DROP TABLE like_dislike');
        $this->addSql('DROP TABLE user_like_dislike');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4058BAC62AF');
        $this->addSql('ALTER TABLE user_profile DROP FOREIGN KEY FK_D95AB4055D83CC1');
        $this->addSql('DROP INDEX IDX_D95AB4058BAC62AF ON user_profile');
        $this->addSql('DROP INDEX IDX_D95AB4055D83CC1 ON user_profile');
        $this->addSql('ALTER TABLE user_profile DROP zip_code, DROP city_id, DROP state_id');
    }
}
