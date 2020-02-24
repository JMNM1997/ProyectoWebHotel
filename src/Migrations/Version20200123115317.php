<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123115317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE planta CHANGE ColorFlor_idColorFlor ColorFlor_idColorFlor INT DEFAULT NULL');
        $this->addSql('ALTER TABLE planta_has_parteutil RENAME INDEX fk_planta_has_parteutil_planta1_idx TO IDX_91893DFD60755F29');
        $this->addSql('ALTER TABLE planta_has_parteutil RENAME INDEX fk_planta_has_parteutil_parteutil1_idx TO IDX_91893DFD439E2B47');
        $this->addSql('ALTER TABLE planta_has_usomedico RENAME INDEX fk_planta_has_usomedico_planta1_idx TO IDX_58ED595060755F29');
        $this->addSql('ALTER TABLE planta_has_usomedico RENAME INDEX fk_planta_has_usomedico_usomedico1_idx TO IDX_58ED5950AD2167FA');
        $this->addSql('ALTER TABLE usomedico_has_producto RENAME INDEX fk_usomedico_has_producto_usomedico1_idx TO IDX_D977591CAD2167FA');
        $this->addSql('ALTER TABLE usomedico_has_producto RENAME INDEX fk_usomedico_has_producto_producto1_idx TO IDX_D977591C9D10239');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE planta CHANGE ColorFlor_idColorFlor ColorFlor_idColorFlor INT NOT NULL');
        $this->addSql('ALTER TABLE planta_has_parteutil RENAME INDEX idx_91893dfd439e2b47 TO fk_Planta_has_ParteUtil_ParteUtil1_idx');
        $this->addSql('ALTER TABLE planta_has_parteutil RENAME INDEX idx_91893dfd60755f29 TO fk_Planta_has_ParteUtil_Planta1_idx');
        $this->addSql('ALTER TABLE planta_has_usomedico RENAME INDEX idx_58ed5950ad2167fa TO fk_Planta_has_UsoMedico_UsoMedico1_idx');
        $this->addSql('ALTER TABLE planta_has_usomedico RENAME INDEX idx_58ed595060755f29 TO fk_Planta_has_UsoMedico_Planta1_idx');
        $this->addSql('ALTER TABLE usomedico_has_producto RENAME INDEX idx_d977591c9d10239 TO fk_UsoMedico_has_Producto_Producto1_idx');
        $this->addSql('ALTER TABLE usomedico_has_producto RENAME INDEX idx_d977591cad2167fa TO fk_UsoMedico_has_Producto_UsoMedico1_idx');
    }
}
