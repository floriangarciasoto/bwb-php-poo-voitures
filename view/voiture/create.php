<form method="get" action="../../controller/router.php">
  <fieldset>
    <legend>Mon formulaire :</legend>
    <p>
      <label for="immat_id">Immatriculation</label> :
      <input type="text" placeholder="Ex : 256AB34" name="immatriculation" id="immat_id" required/>
    </p>
    <p>
      <label for="mq_id">Marque</label> :
      <input type="text" placeholder="Ex : Citroën" name="marque" id="mq_id" required/>
    </p>
    <p>
      <label for="cl_id">Couleur</label> :
      <input type="text" placeholder="Ex : Vert" name="couleur" id="cl_id" required/>
    </p>
    <input type="hidden" name="action" value="created">
    <p>
      <input type="submit" value="Envoyer" />
    </p>
  </fieldset> 
</form>
<p><a href="/controller/router.php?action=readAll">Retour à la liste</a></p>
