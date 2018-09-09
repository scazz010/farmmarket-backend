<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180909052116 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE farms ADD preview_image_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE farms ADD CONSTRAINT FK_1DE06CC4FAE957CD FOREIGN KEY (preview_image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DE06CC4FAE957CD ON farms (preview_image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE _4228e4a00331b5d5e751db0481828e22a2c3c8ef (no BIGINT AUTO_INCREMENT NOT NULL, event_id CHAR(36) NOT NULL COLLATE utf8mb4_bin, event_name VARCHAR(100) NOT NULL COLLATE utf8mb4_bin, payload JSON NOT NULL, metadata JSON NOT NULL, created_at DATETIME NOT NULL, aggregate_version INT UNSIGNED NOT NULL, aggregate_id CHAR(36) NOT NULL COLLATE utf8mb4_bin, aggregate_type VARCHAR(150) NOT NULL COLLATE utf8mb4_bin, UNIQUE INDEX ix_event_id (event_id), UNIQUE INDEX ix_unique_event (aggregate_type, aggregate_id, aggregate_version), INDEX ix_query_aggregate (aggregate_type, aggregate_id, no), PRIMARY KEY(no)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP INDEX UNIQ_1DE06CC4FAE957CD ON farms');
        $this->addSql('ALTER TABLE farms DROP preview_image_id');
    }
}
