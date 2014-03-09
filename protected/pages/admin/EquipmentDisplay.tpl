<prop:ItemTemplate>
    <com:TLightWindow 
        Href="#<%=$this->CommonTemplate->getClientID()%>"
        Title="<%# $this->Data->name %>"
        Height="350"
        Width="600"
        Top="300"
        CssClass="list-item"
    />
    <com:TPanel ID="CommonTemplate">
        <div class="form-center">
            <div class="form-row">
                <com:TLabel ID="NameLabel" Text="Nom : " ForControl="Name" />
                <com:TLabel ID="Name" Text="<%# $this->Data->name %>" />
            </div>
            <div class="form-row">
                <com:TLabel ID="DescriptionLabel" Text="Description : " ForControl="Description" />
                <com:TLabel ID="Description" Text="<%# $this->Data->description %>" />
            </div>
            <div class="form-row">
                <com:TLabel ID="WeightLabel" Text="Poids : " ForControl="Weight" />
                <com:TLabel ID="Weight" Text="<%# $this->Data->weight %>" />
            </div>
            <div class="form-row">
                <com:TLabel ID="CostLabel" Text="Prix : " ForControl="Cost" />
                <com:TLabel ID="Cost" Text="<%# $this->Data->cost %>" />
            </div>
        </div>
    </com:TPanel>
    
</prop:ItemTemplate>