// Classe Erreur : fonctions de validation des champs du formulaire
class Erreur {
    static PositiveNumber(valeur) {
        return valeur < 0;
    }
    static InputVide(Input) {
        return Input === '';
    }
    static InputNotNomb(valeur) {
        return isNaN(valeur);
    }
}
// Classe StockItem : gestion du stockage local des mouvements de stock
class StockItem {
    constructor() {
        // Charger les données déjà stockées dans localStorage ou initialiser un tableau vide
        this.data = JSON.parse(localStorage.getItem('Stock')) || [];
    }
    // Sauvegarder le tableau de stock dans localStorage
    save() {
        localStorage.setItem('Stock', JSON.stringify(this.data));
    }
    // Ajouter une nouvelle entrée de stock
    add(Info) {
        let lastQte = 0;
        if (this.data.length > 0) {
            // Récupérer la dernière quantité de stock existante
            lastQte = this.data[this.data.length - 1].qteStock;
        }
        Info.CalculCout = Calculer.CalculerCout(
            Info.MtTotalSt,
            Info.MtTotalE,
            Info.qteStock,
            Info.qteEntrer
        );
        // Calculer le stock après sortie
        Info.data = Calculer.CalculeStock(lastQte, Info.qteSortie);
        this.data.push(Info);
        this.save();
    }
    // Mettre à jour une entrée existante par index
    update(index, element) {
        this.data[index] = element;
        this.save();
    }
    // Supprimer une entrée par index
    remove(index) {
        this.data.splice(index,1);
        this.save();
    }
}
// Classe Calculer : fonctions utilitaires de calcul du stock et des montants
class Calculer {
    static CalculeStock(lastStock, sortie) {
        // Calculer le stock restant après une sortie
        return lastStock - sortie;
    }
    static QteStock(Qte, cu) {
        // Calculer le montant total (quantité x coût unitaire)
        return Qte * cu;
    }
    static CalculerCout(MtStock, MtEntre, qtStock, qtEntre, cuStock) {
        let total = 0;
        if (Stock.data.length === 0) {
            // Si aucun enregistrement n'existe, utiliser le coût du stock actuel
            total = cuStock;
        } else {
            let nem = MtStock + MtEntre;
            let dem = qtStock + qtEntre;
            total = dem !== 0 ? (nem / dem).toFixed(2) : 0;
        }
        return total;
    }
}
// Instance principale de gestion des mouvements de stock
const Stock = new StockItem();
// Afficher un message d'erreur sous un champ de saisie
const getErreur = (element, message) => {
    const span = document.createElement('span');
    span.innerText = message;
    span.classList.add('error');
    element.after(span);
    scrollTo({
        top: 'center',
        behavior: 'smooth'
    });
};
let edidting = -1;
document.querySelector('button').addEventListener('click', function (e) {
    // Traitement du clic sur le bouton Valider / Mise à jour
    const Information = () => {
        const Inputs = document.querySelectorAll('input');
        const erreur = document.querySelectorAll('.error');
        let valide = true;
        // Supprimer les anciens messages d'erreur
        erreur.forEach(element => element.remove());
        Inputs.forEach(element => {
            if ((element.id === 'oper' || element.id === 'qte-stock' || element.id === 'cu-stock') && Stock.data.length === 0 && Erreur.InputVide(element.value)) {
                getErreur(element, `Le champ ${element.id} est obligatoire`);
                valide = false;
                return;
            }
            if (element.id !== 'oper' && Erreur.InputNotNomb(element.value)) {
                getErreur(element, `Entrer dans ${element.id} Nombre`);
                valide = false;
                return;
            }
            if (Erreur.PositiveNumber(element.value)) {
                getErreur(element, `Le nombre dans ${element.id} doit être positif`);
                valide = false;
                return;
            }
            if (Stock.data.length >= 1 && element.id !== 'qte-stock' && element.id !== 'Rec' && Erreur.InputVide(element.value)) {
                getErreur(element, `Le champ ${element.id} est obligatoire`);
                valide = false;
                return;
            }
        });
        if (valide) {
            let lastQte;
            switch (true) {
                case edidting === 0:
                    // Modification de la première entrée
                    lastQte = +document.querySelector('#qte-stock').value;
                    break;
                case edidting > 0:
                    // Modification d'une entrée existante
                    lastQte = Stock.data[edidting - 1].qteStock;
                    break;
                default:
                    // Nouvelle entrée : utiliser le dernier stock ou la valeur saisie
                    lastQte = Stock.data.length > 0
                        ? Stock.data[Stock.data.length - 1].qteStock
                        : +document.querySelector('#qte-stock').value;
            }
            let mtTotalS = Calculer.QteStock(document.querySelector('#qte-sortie').value, document.querySelector('#cu-sortie').value);
            let QteStock = Calculer.CalculeStock(lastQte, mtTotalS);
            let data = {
                date: new Date().toLocaleString('fr-FR'),
                NomP: document.querySelector('#oper').value || '',
                qteEntrer: +document.querySelector('#qte-enter').value || 0,
                cuEntrer: +document.querySelector('#cu-enter').value || 0,
                MtTotalE: Calculer.QteStock(document.querySelector('#qte-enter').value, document.querySelector('#cu-enter').value),
                qteSortie: +document.querySelector('#qte-sortie').value || 0,
                CuSortie: +document.querySelector('#cu-sortie').value || 0,
                MtTotalS: mtTotalS,
                qteStock: QteStock,
                cuStock: +document.querySelector('#cu-stock').value || 0,
                MtTotalSt: Calculer.QteStock(QteStock, document.querySelector('#cu-stock').value)
            };
            switch (true) {
                case edidting === -1:
                    // Ajouter une nouvelle entrée
                    Stock.add(data);
                    break;
                default:
                    // Mettre à jour une entrée existante
                    Stock.update(edidting, data);
                    break;
            }
            recalcul();
            return;
        }
        // Empêcher l'envoi du formulaire si les données ne sont pas valides
        e.preventDefault();
    };
    Information();
    this.innerText = 'Valider';
});
// Supprimer une ligne dans le tableau
const deleteRow = (index) => {
    Stock.remove(index);
    renderTable();
};
// Recalculer les montants et mettre à jour les données
const recalcul = () => {
    if (document.querySelector('button').innerText === 'mise à jour') {
        let Stocks = Stock.data.length === 0 ? +document.querySelector('#qte-stock').value : Stock.data[0].qteStock;
        var CalculeC = 0;
        Stock.data.forEach((element, index) => {
            let newCalcul = Calculer.CalculeStock(Stocks, element.MtTotalS);
            let newQteS = Calculer.QteStock(newCalcul, element.cuStock);
            CalculeC = Calculer.CalculerCout(
                Stock.data[0].MtTotalSt,
                Stock.data[index].MtTotalE,
                Stock.data[0].qteStock,
                Stock.data[index].qteEntrer,
                Stock.data[0].cuStock
            );
            element.MtTotalSt = newQteS;
            element.qteStock = newCalcul;
            Stocks = newCalcul;
        });
    }
    Stock.save();
    renderTable();
};
// Afficher le tableau de stock et le coût moyen pondéré unique
const renderTable = () => {
    document.querySelector('#qte-stock').readOnly = Stock.data.length > 0;
    document.querySelector('#qte-enter').readOnly = Stock.data.length === 0;
    document.querySelector('#cu-enter').readOnly = Stock.data.length === 0;
    document.querySelector('#qte-sortie').readOnly = Stock.data.length === 0;
    document.querySelector('#cu-sortie').readOnly = Stock.data.length === 0;
    const bodytab = document.querySelector('tbody');
    bodytab.innerHTML = '';
    let CalculeC = 0;
    Stock.data.forEach((element, index) => {
        bodytab.innerHTML += `
            <tr>
                <td>${element.date}</td>
                <td>${element.NomP}</td>
                <td>${element.qteEntrer}</td>
                <td>${element.cuEntrer}</td>
                <td>${element.MtTotalE}</td>
                <td>${element.qteSortie}</td>
                <td>${element.CuSortie}</td>
                <td>${element.MtTotalS}</td>
                <td>${element.qteStock}</td>
                <td>${element.cuStock}</td>
                <td>${element.MtTotalSt}</td>
                <td><button type='button' class='btn-supprimer' onclick="deleteRow(${index})">Supprimer</button></td>
                <td><button type='button' onclick="updateT(${index})" name="Modifier">Modifier</button></td>
            </tr>
        `;
        CalculeC = Calculer.CalculerCout(
            Stock.data[0].MtTotalSt,
            Stock.data[index].MtTotalE,
            Stock.data[0].qteStock,
            Stock.data[index].qteEntrer,
            Stock.data[0].cuStock
        );
    });
    document.querySelector('span').innerText = CalculeC;
};
// Préparer le formulaire pour modifier une ligne existante
const updateT = (index) => {
    edidting = index;
    document.querySelector('button:nth-child(1)').innerText = 'mise à jour';
    const item = Stock.data[edidting];
    document.querySelector('#qte-stock').readOnly = index > 0;
    document.querySelector('#oper').value = item.NomP;
    document.querySelector('#qte-enter').value = item.qteEntrer;
    document.querySelector('#cu-enter').value = item.cuEntrer;
    document.querySelector('#qte-sortie').value = item.qteSortie;
    document.querySelector('#cu-sortie').value = item.CuSortie;
    document.querySelector('#qte-stock').value = item.qteStock;
    document.querySelector('#cu-stock').value = item.cuStock;
    scrollTo({
        top: 'center',
        behavior: 'smooth'
    });
};
const Recherche = () => {
    const InputRe = document.querySelector('#Rec').value;
    const tbody = document.querySelector('tbody');
    const filter = Stock.data.filter(produit => {
        return produit.NomP.toLowerCase().includes(InputRe.toLowerCase());
    })
    if (filter.length > 0) {
        const result = filter.map(prod=>{
            return`
               <tr>
                    <td>${prod.date}</td>
                    <td>${prod.NomP}</td>
                    <td>${prod.qteEntrer}</td>
                    <td>${prod.cuEntrer}</td>
                    <td>${prod.MtTotalE}</td>
                    <td>${prod.qteSortie}</td>
                    <td>${prod.CuSortie}</td>
                    <td>${prod.MtTotalS}</td>
                    <td>${prod.qteStock}</td>
                    <td>${prod.cuStock}</td>
                    <td>${prod.MtTotalSt}</td>
                   <td><button type='button' class='btn-supprimer' onclick="deleteRow(${prod.id})">Supprimer</button></td>
                <td><button type='button' onclick="updateT(${prod.id})">Modifier</button></td>
                </tr>
               
               `;
               
        })
        tbody.innerHTML = result;
        
    } else {
        tbody.innerHTML = `<tr><td colspan="13">Aucun résultat</td></tr>`;

    }


}
// Charger les données et afficher le tableau au chargement de la page
window.onload = () => {
    renderTable();
};
