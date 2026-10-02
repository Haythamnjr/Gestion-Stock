<?php

include_once "Connect.php";

// Classe Stock qui hérite de la connexion à la base de données
class Stock extends Connexion
{
    // Propriétés du mouvement de stock
    private $id = 0, $date = "", $type_oper = "", $qte_entree = 0, $cu_entree = 0, $qte_sortie = 0, $cu_sortie = 0, $qte_stock_finale = 0, $cu_stock_moyen = 0;

    public function __construct($Table = [])
    {
        parent::__construct();
        // Initialiser les propriétés à partir des données POST
        (string)$this->type_oper = $Table['oper'] ?? null;
        (float)$this->qte_entree = $Table['qte_entree'] ?? null;
        (float)$this->cu_entree = $Table['cu_entree'] ?? null;
        (float)$this->qte_sortie = $Table['qte_sortie'] ?? null;
        (float)$this->cu_sortie = $Table['cu_sortie'] ?? null;
        (float)$this->qte_stock_finale = $Table['qte_stock'] ?? null;
        (float)$this->cu_stock_moyen = $Table['cu_stock'] ?? null;
    }

    // Getter / setter pour l'identifiant
    public function getID()
    {
        return $this->id;
    }
    public function setID(int $id)
    {
        $this->id = $id;
    }

    // Getter / setter pour la date
    public function getDate()
    {
        return $this->date;
    }
    public function setDate(string $date)
    {
        $this->date = $date;
    }

    // Getter / setter pour le type d'opération
    public function getTypeOper()
    {
        return $this->type_oper;
    }
    public function setTypeOper(string $type_oper)
    {
        $this->type_oper = $type_oper;
    }

    // Getter / setter pour la quantité entrée
    public function getQteEntree()
    {
        return $this->qte_entree;
    }
    public function setQteEntree(int $qte_entree)
    {
        $this->qte_entree = $qte_entree;
    }

    // Getter / setter pour le coût unitaire entrée
    public function getCuEntree()
    {
        return $this->cu_entree;
    }
    public function setCuEntree(float $cu_entree)
    {
        $this->cu_entree = $cu_entree;
    }

    // Getter / setter pour la quantité sortie
    public function getQteSortie()
    {
        return $this->qte_sortie;
    }
    public function setQteSortie(int $qte_sortie)
    {
        $this->qte_sortie = $qte_sortie;
    }

    // Getter / setter pour le coût unitaire sortie
    public function getCuSortie()
    {
        return $this->cu_sortie;
    }
    public function setCuSortie(float $cu_sortie)
    {
        $this->cu_sortie = $cu_sortie;
    }

    // Getter / setter pour la quantité de stock finale
    public function getQteStockFinale()
    {
        return $this->qte_stock_finale;
    }
    public function setQteStockFinale(int $qte_stock_finale)
    {
        $this->qte_stock_finale = $qte_stock_finale;
    }

    // Getter / setter pour le coût unitaire moyen du stock
    public function getCuStockMoyen()
    {
        return $this->cu_stock_moyen;
    }
    public function setCuStockMoyen(float $cu_stock_moyen)
    {
        $this->cu_stock_moyen = $cu_stock_moyen;
    }

    // Insérer une nouvelle ligne dans la table MOUVEMENTS_STOCK
    public function AjouterBase()
    {
        if ($this->Conect) {
            try {
                $Ajouter = $this->Conect->prepare("INSERT INTO mouvements_stock(date , type_oper , qte_entree , cu_entree , qte_sortie , cu_sortie , qte_stock_finale , cu_stock_moyen) VALUES(Now(),?,?,?,?,?,?,?)");
                // Assurer que les valeurs numériques vides deviennent zéro
                (int)$qte_e = ($this->getQteEntree() === '' || $this->getQteEntree() === null) ? 0 : $this->getQteEntree();
                (float)$cu_e  = ($this->getCuEntree() === '' || $this->getCuEntree() === null)   ? 0 : $this->getCuEntree();
                (int)$qte_s = ($this->getQteSortie() === '' || $this->getQteSortie() === null) ? 0 : $this->getQteSortie();
                (float)$cu_s  = ($this->getCuSortie() === '' || $this->getCuSortie() === null)   ? 0 : $this->getCuSortie();
                (int)$qte_f = ($this->getQteStockFinale() === '' || $this->getQteStockFinale() === null) ? 0 : $this->getQteStockFinale();
                (float)$cu_m  = ($this->getCuStockMoyen() === '' || $this->getCuStockMoyen() === null)     ? 0 : $this->getCuStockMoyen();

                $Ajouter->execute([
                    htmlspecialchars((string)$this->getTypeOper()),
                    (int)$qte_e,
                    (float)$cu_e,
                    (int)$qte_s,
                    (float)$cu_s,
                    (int)$qte_f,
                    (float)$cu_m
                ]);
            } catch (Exception $e) {
                error_log("les donnes est no Enregistre " . $e->getMessage());
            }
        }
    }
    public function SupprimerBase()
    {
        if ($this->Conect) {
            try {
                $Supprimer = $this->Conect->prepare("DELETE FROM mouvements_stock WHERE id = ?");
                $Supprimer->execute([$this->getId()]);
            } catch (Exception $e) {
                error_log("il y'a une Probléme de suppression " . $e->getMessage());
            }
        }
    }
    public function ModifierBase()
    {
        if ($this->Conect) {
            try {
                $Modifier = $this->Conect->prepare("UPDATE mouvements_stock SET type_oper = ?, qte_entree = ?, cu_entree = ?, qte_sortie = ?, cu_sortie = ?, qte_stock_finale = ?, cu_stock_moyen = ? WHERE id = ?");
                $Modifier->execute([
                    htmlspecialchars((string)$this->getTypeOper()),
                    (int)$this->getQteEntree(),
                    (float)$this->getCuEntree(),
                    (int)$this->getQteSortie(),
                    (float)$this->getCuSortie(),
                    (int)$this->getQteStockFinale(),
                    (float)$this->getCuStockMoyen(),
                    $this->getId()
                ]);
            } catch (Exception $e) {
                error_log("il y'a une Probléme de modification " . $e->getMessage());
            }
        }
    }
    // Récupérer toutes les lignes de mouvements de stock
    public function Afficher()
    {
        if ($this->Conect) {
            try {
                $Ajouter = $this->Conect->prepare("SELECT * FROM MOUVEMENTS_STOCK");
                $Ajouter->execute([]);
                return $Ajouter->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Stock::class);
            } catch (Exception $e) {
                error_log("il y'a une Probléme de l'affichage " . $e->getMessage());
            }
        }
        return [];
    }
}
