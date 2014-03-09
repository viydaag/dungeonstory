<com:TView ID="ClasseView">
    <div class="main-header">
        Classe
    </div>
    <div class="main-content">             
        <br/><br/>
        <table width="700">
            <tr style="vertical-align:top">
                <td>
                    <com:TActiveListBox ID="ClasseList"  
                        DataTextField="nom"
                        DataValueField="id"
                        AutoPostBack="true"
                        OnSelectedIndexChanged="changeClasseDescription"
                    />
                </td>
                <td>          
                    <com:TActiveTextBox ID="ClasseDescription"
                        TextMode="MultiLine"
                        Rows="6"
                        Columns="50"
                        ReadOnly="true"
                    />
                </td>
            </tr>
        </table>
    </div>
</com:TView>
