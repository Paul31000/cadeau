# projet
mettre la date du commentaire en francais
prevoir un moyen d'editer le commentaire et de le supprimer


# version symfony 6

## bloquer l'util pour un controleur
#[IsGranted('ROLE_AUTHENTIFIE')]
use Symfony\Component\Security\Http\Attribute\IsGranted;

sensio framework extra bundle inutile

> $this->denyAccessUnlessGranted('ROLE_AUTHENTIFIE');

## NPM compilateur

utilisation de encore et de stimulus. et non asset mapper

## confirm page

window.confirm("Do you really want to leave?")

## bouton action
### methode bouton 
#### formulaire 
<form class="col-6 d-flex justify-content-around" action="{{ url('delete_indicateur_comptage_spec', {'id_comptage': comptage.id,'id_utilisation_spec': util.id}) }}" method="post">
    <input  type="hidden" name="token" value="{{ csrf_token('delete_indicateur') }}"/>
    <button class="vert" type="submit">Retirer spec {{util.lienAttributOdr}} a {{ composite.nomSansArticle}}</button>
</form>

#### controleur
{{ render(controller('App\\Controller\\MessageController::index', {'idEntiteeLiee':cadeau.id, 'entiteeLiee':'cadeau'})) }}

### methode symfony (je n'ai pas compris comment faire marcher)
#### formulaire 
<a href="{{ path('app_message_delete', {'id': message.id}) }}">delete</a>

#### controleur
if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->getPayload()->getString('_token'))) {

## recup un truc du formulaire

> $request->get('UtilResponsable')["new_notes_odr"]

## utilisateur connecte

> $this->getUser()


## flash message

> $this->addFlash('success', 'Vous avez créé une demande avec succès');

## feuilles de style et de code js

dans twig lier les feuilles:
encore_entry_script_tags('le nom declare dans webpack')
{{ encore_entry_link_tags('app') }}


## TODO

Tom select
login
Ajouter .env à la production
