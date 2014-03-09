<div class="sideheader">
</div>

<div class="sidecontent">
	Bienvenue, <b><%= $this->User->Name %></b>!

    <br /><br />
    <com:THyperLink
        NavigateUrl="<%= $this->Service->constructUrl('users.ViewUser', array('userId'=>$this->User->Profile->id)) %>"
        Text="Profil"
        Target="_parent"
        Visible="<%= !$this->User->IsGuest %>" 
    />
    <com:TControl Visible="<%= !$this->User->IsGuest && $this->User->hasPerso() %>" >
        <com:THyperLink
            NavigateUrl="<%= $this->Service->constructUrl('persos.ViewPerso', array('persoId'=>$this->User->Perso->id)) %>"
            Text="Personnage"
            Target="_parent"
            Visible="<%= !$this->User->IsGuest && $this->User->hasPerso() %>" 
        />
    </com:TControl>
    <com:THyperLink
        NavigateUrl="<%= $this->Service->constructUrl('persos.NewPerso') %>"
        Text="Nouveau Personnage"
        Target="_parent"
        Visible="<%= !$this->User->IsGuest && !$this->User->hasPerso() && $this->User->Profile->isActive() %>" 
    />
    <com:THyperLink
        NavigateUrl="<%= $this->Service->constructUrl('combat.ListEnemy') %>"
        Text="Combat"
        Target="_parent"
        Visible="<%= !$this->User->IsGuest && $this->User->hasPerso() && $this->User->Profile->isActive() %>" 
    />
    <br />
    
    <br /><br />
    <com:TLinkButton Text="Déconnexion" OnClick="logout" />
</div>

<div class="sidefooter">
</div>