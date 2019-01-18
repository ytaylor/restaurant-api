<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190116195529 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ingredients_allergens (ingredients_id INT NOT NULL, allergens_id INT NOT NULL, INDEX IDX_6886B58E3EC4DCE (ingredients_id), INDEX IDX_6886B58E711662F1 (allergens_id), PRIMARY KEY(ingredients_id, allergens_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dishes_ingredients (dishes_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_837A1997A05DD37A (dishes_id), INDEX IDX_837A19973EC4DCE (ingredients_id), PRIMARY KEY(dishes_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredients_allergens ADD CONSTRAINT FK_6886B58E3EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE ingredients_allergens ADD CONSTRAINT FK_6886B58E711662F1 FOREIGN KEY (allergens_id) REFERENCES allergens (id)');
        $this->addSql('ALTER TABLE dishes_ingredients ADD CONSTRAINT FK_837A1997A05DD37A FOREIGN KEY (dishes_id) REFERENCES dishes (id)');
        $this->addSql('ALTER TABLE dishes_ingredients ADD CONSTRAINT FK_837A19973EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE ingredients_allergens');
        $this->addSql('DROP TABLE dishes_ingredients');
    }
}
