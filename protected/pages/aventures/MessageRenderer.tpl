<div>
	<table border="1" cellspacing="1" style="width:720px;table-layout:fixed">
        <tr>
            <td rowspan="2" style="width:200px;text-align:center;">
                <com:TImage ImageUrl="<%# $this->Data->perso->image %>" /> <br/>
                <com:TControl Visible="<%# $this->Data->perso->nom == 'Maitre de l\'aventure' %>" >
                	<com:TLiteral Text="<%# $this->Data->perso->nom %>" />
                </com:TControl>
                <com:TControl Visible="<%# $this->Data->perso->nom != 'Maitre de l\'aventure' %>" >
                	<com:THyperLink 
                        Text="<%# $this->Data->perso->nom %>" 
                        NavigateUrl="<%# $this->Service->constructUrl('persos.ViewPerso', array('persoId'=>$this->Data->persoId)) %>"
                    /> <br />
                	<com:TLiteral Text="<%# $this->Data->perso->race->nom %>" /> <com:TLiteral Text="<%# $this->Data->perso->ClasseNiveauString %>" /> <br/>
                	Niveau <com:TLiteral Text="<%# $this->Data->perso->niveau %>" /> <br/>
                    <com:TLiteral Text="<%# $this->Data->dateCreation %>" />
                </com:TControl>                
            </td>
            <td style="width:520px">
                <com:TControl Visible="<%= ($this->User->hasPerso() && $this->User->Perso->id == $this->Data->persoId) || ($this->User->isAdmin) %>">
                    <com:THyperLink 
                    	Text="<%# $this->Data->titre %>"
                    	NavigateUrl="<%# $this->Service->constructUrl('aventures.ViewMessage',array('aventureId'=>$this->Data->aventureId,'messageId'=>$this->Data->id)) %>"
                    />
                </com:TControl>
                <com:TControl Visible="<%= (!$this->User->hasPerso() || $this->User->Perso->id != $this->Data->persoId) && (!$this->User->isAdmin) %>">
                    <com:TLiteral Text="<%# $this->Data->titre %>" />
                </com:TControl>
            </td>
        </tr>
        <tr>
            <td><com:DSLiteral Text="<%# $this->Data->texte %>" HLengh="70" /></td>
        </tr>
    </table>

</div>