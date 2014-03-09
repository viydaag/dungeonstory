<com:TRepeater ID="ImageRepeater" EnableViewState="true">        
    <prop:ItemTemplate>
    <div id="imagePerso">
        <com:TRadioButton
                ID="RadioButton" 
                UniqueGroupName="RadioGroup" 
                Text=""
                InputAttributes.value="<%# $this->Data['url'] %>"
                Visible="<%= $this->TemplateControl->WithRadioButton %>"
        />
        <com:TImage ImageUrl="<%# $this->Data['url'] %>" />
        <!--echo <%# $this->Data['url'] %> -->
        </div>
    </prop:ItemTemplate>
</com:TRepeater>