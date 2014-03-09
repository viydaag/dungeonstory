<div class="sideheader">
</div>

<div class="sidecontent">
	<h2>
    	Connexion
    </h2>
    <span>Votre nom d'usager:</span>
    <com:TRequiredFieldValidator
        ControlToValidate="Username"
        ErrorMessage="Veuillez indiquer votre nom."
        Display="Dynamic" />
    <br/>
    <com:TTextBox ID="Username" />
     
    <br/>
    <span>Mot de passe:</span>
    <br/>
    <com:TTextBox ID="Password" TextMode="Password" />
    <br/>
    <com:TCustomValidator
        ControlToValidate="Password"
        Display="Dynamic"
        OnServerValidate="validateUser" 
    />
     
    <br/>
    <com:TLinkButton
    	ID="ConnexionButton"
    	Text="Connexion" 
        OnClick="loginButtonClicked" 
    />
    <com:THyperLink
    	Text="Nouveau joueur"
        NavigateUrl="<%= $this->Service->constructUrl('users.NewUser') %>"
        Visible="<%= $this->User->IsGuest %>"
    />	
</div>

<div class="sidefooter">
</div>