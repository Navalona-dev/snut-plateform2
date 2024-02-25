<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104165455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE commande_semestrielle ADD CONSTRAINT FK_50061BC554432752 FOREIGN KEY (annee_previsionnelle_id) REFERENCES annee_previsionnelle (id)');
        $this->addSql('CREATE INDEX IDX_50061BC554432752 ON commande_semestrielle (annee_previsionnelle_id)');
        $this->addSql('ALTER TABLE commande_trimestrielle CHANGE annee_previsionnelle_id annee_previsionnelle_id INT DEFAULT NULL, CHANGE slug slug VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE commande_trimestrielle ADD CONSTRAINT FK_149D976154432752 FOREIGN KEY (annee_previsionnelle_id) REFERENCES annee_previsionnelle (id)');
        $this->addSql('ALTER TABLE pvrd ADD CONSTRAINT FK_4DD1D557B07B8B81 FOREIGN KEY (responsable_district_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE pvrd ADD CONSTRAINT FK_4DD1D557B08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('ALTER TABLE pvrd ADD CONSTRAINT FK_4DD1D55798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE pvrd_produit ADD CONSTRAINT FK_82F488152292E50 FOREIGN KEY (pvrd_id) REFERENCES pvrd (id)');
        $this->addSql('ALTER TABLE pvrd_produit ADD CONSTRAINT FK_82F48815F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE commande_semestrielle DROP FOREIGN KEY FK_50061BC554432752');
        $this->addSql('DROP INDEX IDX_50061BC554432752 ON commande_semestrielle');
        $this->addSql('ALTER TABLE commande_trimestrielle DROP FOREIGN KEY FK_149D976154432752');
        $this->addSql('ALTER TABLE commande_trimestrielle CHANGE annee_previsionnelle_id annee_previsionnelle_id INT NOT NULL, CHANGE slug slug VARCHAR(25) NOT NULL');
        $this->addSql('ALTER TABLE pvrd DROP FOREIGN KEY FK_4DD1D557B07B8B81');
        $this->addSql('ALTER TABLE pvrd DROP FOREIGN KEY FK_4DD1D557B08FA272');
        $this->addSql('ALTER TABLE pvrd DROP FOREIGN KEY FK_4DD1D55798260155');
        $this->addSql('ALTER TABLE pvrd_produit DROP FOREIGN KEY FK_82F488152292E50');
        $this->addSql('ALTER TABLE pvrd_produit DROP FOREIGN KEY FK_82F48815F347EFB');
    }
}
