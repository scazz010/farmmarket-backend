<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180915225248 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE farms ADD farmer_id VARCHAR(255) DEFAULT NULL, ADD total_revenue_amount INT NOT NULL, ADD total_revenue_currency_code VARCHAR(3) NOT NULL, DROP total_revenue');
        $this->addSql('ALTER TABLE farms ADD CONSTRAINT FK_1DE06CC413481D2B FOREIGN KEY (farmer_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1DE06CC413481D2B ON farms (farmer_id)');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B81704465FCFA0D FOREIGN KEY (farm_id) REFERENCES farms (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE farms DROP FOREIGN KEY FK_1DE06CC413481D2B');
        $this->addSql('DROP INDEX UNIQ_1DE06CC413481D2B ON farms');
        $this->addSql('ALTER TABLE farms ADD total_revenue VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP farmer_id, DROP total_revenue_amount, DROP total_revenue_currency_code');
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B81704465FCFA0D');
        $this->addSql('ALTER TABLE users CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
