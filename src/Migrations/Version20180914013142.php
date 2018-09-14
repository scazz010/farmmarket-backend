<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180914013142 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sales (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', farm_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_6B81704465FCFA0D (farm_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE farms ADD total_sales INT NOT NULL, ADD total_customers INT NOT NULL, ADD total_revenue VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sales');
        $this->addSql('ALTER TABLE farms DROP total_sales, DROP total_customers, DROP total_revenue');
    }
}
