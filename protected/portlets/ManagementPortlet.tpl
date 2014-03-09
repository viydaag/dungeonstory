<h2>Gestion</h2>

<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListUser') %>"
    Text="Joueurs"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListPerso') %>"
    Text="Personnages"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListRace') %>"
    Text="Races"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>   
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListClasse') %>"
    Text="Classes"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListAlignement') %>"
    Text="Alignements"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListRegion') %>"
    Text="Régions"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListSkill') %>"
    Text="Comp&eacute;tences"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListImage') %>"
    Text="Images"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('info.ListNouvelle') %>"
    Text="Nouvelles"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
	NavigateUrl="<%= $this->Service->constructUrl('admin.ListFeat') %>"
    Text="Caractéristiques"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
	NavigateUrl="<%= $this->Service->constructUrl('admin.ListLevel') %>"
    Text="Niveaux"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
	NavigateUrl="<%= $this->Service->constructUrl('admin.ListWeaponType') %>"
    Text="Types d'arme"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListArmorType') %>"
    Text="Types d'armure"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
	NavigateUrl="<%= $this->Service->constructUrl('admin.ListMonster') %>"
    Text="Monstres"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListShop') %>"
    Text="Magasins"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListEquipment') %>"
    Text="Équipement"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>
<com:THyperLink
    NavigateUrl="<%= $this->Service->constructUrl('admin.ListDamageType') %>"
    Text="Types de dommage"
    Target="_parent"
    Visible="<%= $this->User->IsAdmin %>"
/>