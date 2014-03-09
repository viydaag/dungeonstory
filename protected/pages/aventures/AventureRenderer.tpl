<div>
        <tr>
            <td width="400" align="left">
                <com:THyperLink Text="<%# $this->Data->nom %>"
                    NavigateUrl="<%# $this->Service->constructUrl('aventures.ListMessage',array('aventureId'=>$this->Data->id)) %>" />
            </td>
            <td width="200" align="center">
                <com:TLiteral Text="<%# $this->Data->getStringStatut() %>" />
            </td>
            <td width="100" align="center">
                <com:TCheckBox Text="" Checked="<%= $this->User->hasPerso() && $this->User->Perso->aventureId == $this->Data->id %>" Enabled="false"/>
            </td>
            <td width="100" align="center">
                <com:THyperLink Text="Voir"
                    NavigateUrl="<%# $this->Service->constructUrl('aventures.ViewAventure',array('aventureId'=>$this->Data->id)) %>" />
            </td>
        </tr>

</div>