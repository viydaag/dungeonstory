<com:TClientScript PradoScripts="effects" />
<com:TClientScript ScriptUrl=<%~ LightWindow\javascript\lightwindow.js %> />
<com:TPanel ID="plContentInline" Style="display:none;">
    <com:TPanel ID="plWrapContentInline"/>
</com:TPanel>
<a href="<%=$this->Href%>" 
	params="<%=$this->Params%>" 
	class="lightwindow <%=$this->CssClass%>" 
	title="<%=$this->Title%>" 
	caption="<%=$this->Caption%>" 
	author="<%=$this->Author%>">
		<%=$this->Title%>
</a>