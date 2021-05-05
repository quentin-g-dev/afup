<?php
namespace AppBundle\Site\Model;

use CCMBenchmark\Ting\Entity\NotifyProperty;
use CCMBenchmark\Ting\Entity\NotifyPropertyInterface;
use Symfony\Component\Validator\Constraints as Assert;

class Feuille implements NotifyPropertyInterface 
{
    use NotifyProperty;

    const ID_FEUILLE_ANTENNES = 71;
    const ID_FEUILLE_ASSOCIATION = 74;
    const ID_FEUILLE_COLONNE_DROITE = 1;
    const ID_FEUILLE_HEADER = 21;
    const ID_FEUILLE_FOOTER = 38;
    const ID_FEUILLE_NOS_ACTIONS = 96;

    public $id;
    public $id_parent;
    public $nom;
    public $lien;
    public $alt;
    public $image;
    public $position;
    public $date;
    public $etat;
    public $patterns;

    public function getId() {
        return $this->id;
    }
     
    public function setId($id)
    {
        $this->propertyChanged('id', $this->id, $id);
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }
    
    public function setNom($nom) {
        $this->propertyChanged('nom', $this->nom, $nom);
        $this->nom = $nom;
    }

    public function getIdParent() {
        return $this->id_parent;
    }
    
    public function setIdParent($id) {
        $this->propertyChanged('id_parent', $this->id_parent, $id);
        $this->id_parent = $id;
    }

    
    public function getLien() {
        return $this->lien;
    }
    
    public function setLien($lien) {
        $this->propertyChanged('lien', $this->lien, $lien);
        $this->lien = $lien;
    }

    public function getAlt() {
        return $this->alt;
    }
    
    public function setAlt($alt) {
        $this->alt = $alt;
        $this->propertyChanged('alt', $this->alt, $alt);
    }

    public function getImage() {
        return $this->image;
    }
    
    public function setImage($image) {
        $this->propertyChanged('image', $this->image, $image);
        $this->image = $image;
    }

    public function getPosition() {
        return $this->position;
    }
    
    public function setPosition($position) {
        $this->propertyChanged('position', $this->position, $position);
        $this->position = $position;
    }

    public function getDate() {
        return $this->date;
    }
    
    public function setDate($date) {
        $this->propertyChanged('date', $this->date, $date);
        $this->date = $date;
    }

    public function getEtat() {
        return $this->etat;
    }
    
    public function setEtat($etat) {
        $this->propertyChanged('etat', $this->etat, $etat);
        $this->etat = $etat;
    }

    public function getPatterns () {
        return $this->patterns;
    }
    
    public function setPatterns ($patterns = 0) {
        $patterns = is_null($patterns) ? 0 : $patterns;
        $this->propertyChanged('patterns', $this->patterns, $patterns);
        $this->patterns = $patterns;
    }

   
    function inserer()
    {
        if ($this->id > 0) {
            $this->supprimer();
        }
        $requete = 'INSERT INTO afup_site_feuille
        			SET
        			id_parent = ' . $this->bdd->echapper($this->id_parent) . ',
        			nom       = ' . $this->bdd->echapper($this->nom) . ',
        			lien      = ' . $this->bdd->echapper($this->lien) . ',
        			alt       = ' . $this->bdd->echapper($this->alt) . ',
        			image     = ' . $this->bdd->echapper($this->image) . ',
        			position  = ' . $this->bdd->echapper($this->position) . ',
        			date      = ' . $this->bdd->echapper($this->date) . ',
        			patterns  = ' . $this->bdd->echapper($this->patterns) . ',
        			etat    = ' . $this->bdd->echapper($this->etat);
        if ($this->id > 0) {
            $requete .= ', id = ' . $this->bdd->echapper($this->id);
        }

        return $this->bdd->executer($requete);
    }

    function modifier()
    {
        $requete = 'UPDATE afup_site_feuille
        			SET
        			id_parent = ' . $this->bdd->echapper($this->id_parent) . ',
        			nom       = ' . $this->bdd->echapper($this->nom) . ',
        			lien      = ' . $this->bdd->echapper($this->lien) . ',
        			alt       = ' . $this->bdd->echapper($this->alt) . ',
        			image     = ' . $this->bdd->echapper($this->image) . ',
        			position  = ' . $this->bdd->echapper($this->position) . ',
        			date      = ' . $this->bdd->echapper($this->date) . ',
        			patterns  = ' . $this->bdd->echapper($this->patterns) . ',
        			etat      = ' . $this->bdd->echapper($this->etat) . '
        			WHERE id  = ' . (int)$this->id;

        return $this->bdd->executer($requete);
    }

    function remplir($f)
    {
        $this->id = $f['id'];
        $this->id_parent = $f['id_parent'];
        $this->nom = $f['nom'];
        $this->lien = $f['lien'];
        $this->alt = $f['alt'];
        $this->image = $f['image'];
        $this->position = $f['position'];
        $this->date = $f['date'];
        $this->etat = $f['etat'];
        $this->patterns = $f['patterns'];
    }

    function exportable()
    {
        return array(
            'id' => $this->id,
            'id_parent' => $this->id_parent,
            'nom' => $this->nom,
            'lien' => $this->lien,
            'alt' => $this->alt,
            'image' => $this->image,
            'position' => $this->position,
            'date' => date('Y-m-d', $this->date),
            'etat' => $this->etat,
            'patterns' => $this->patterns,
        );
    }

    function charger()
    {
        $requete = 'SELECT *
                    FROM afup_site_feuille
                    WHERE id = ' . $this->bdd->echapper($this->id);
        $f = $this->bdd->obtenirEnregistrement($requete);
        $this->remplir($f);
    }

    function supprimer()
    {
        $requete = 'DELETE FROM afup_site_feuille WHERE id = ' . $this->bdd->echapper($this->id);
        return $this->bdd->executer($requete);
    }

    function positionable()
    {
        $positions = array();
        for ($i = 9; $i >= -9; $i--) {
            $positions[$i] = $i;
        }
        return $positions;
    }

    
}
