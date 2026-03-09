<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260309033344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_like_dislike CHANGE does_like does_like TINYINT NOT NULL');
        $this->addSql('ALTER TABLE user_profile DROP INDEX IDX_D95AB405A76ED395, ADD UNIQUE INDEX UNIQ_D95AB405A76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_like_dislike CHANGE does_like does_like TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE user_profile DROP INDEX UNIQ_D95AB405A76ED395, ADD INDEX IDX_D95AB405A76ED395 (user_id)');
    }
}
