<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718160731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` ADD ship_addr VARCHAR(255) NOT NULL, ADD ship_city VARCHAR(255) NOT NULL, ADD ship_zipcode VARCHAR(255) NOT NULL, ADD bill_addr VARCHAR(255) NOT NULL, ADD bill_city VARCHAR(255) NOT NULL, ADD bill_zipcode VARCHAR(255) NOT NULL, ADD ship_lname VARCHAR(255) NOT NULL, ADD ship_fname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE code code VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP ship_addr, DROP ship_city, DROP ship_zipcode, DROP bill_addr, DROP bill_city, DROP bill_zipcode, DROP ship_lname, DROP ship_fname');
        $this->addSql('ALTER TABLE product CHANGE price price NUMERIC(11, 2) NOT NULL, CHANGE code code VARCHAR(255) DEFAULT NULL');
    }
}
