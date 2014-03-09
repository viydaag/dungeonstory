<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <com:THead ID="firstHead" Title="<%$ SiteTitle %>" ShortcutIcon="favicon.ico">
        <com:TMetaTag HttpEquiv="Content-Type" Content="text/html; charset=UTF-8" />
        <com:TMetaTag Name="keywords" Content="Dungeon Story,DS,D&amp;D,Role play" />        
	</com:THead>

    <body>
        <com:TForm>
            <div id="page" class="wrap">
                <!--
                <script type="text/javascript">
                    var wf_pbb_object = [
                    {bc:"rgb(245, 211, 67)"},
                    {img:"carte-back.png", mm:true, ms:false, mms:4, mss:10, mmd:1, mso:"v", msd:1, im:"pattern", pr:"both", mma:"both", ofs:{x:0, y:0}, zi:1, sr:false, sb:false, isr:false, isb:false}
                    ];
                </script>
                <script type="text/javascript" src="http://web-features.net/api/wf.pbb.api.js"></script>
                -->
            
            	<div class="top">
				</div>

                <div id="topbar" class="navbar">
                    <com:Application.Common.AxListMenu>
                        <com:AxListMenuItem Text="Accueil" PagePath="info.ListNouvelle" 
                            NavigateUrl="<%= $this->Service->constructUrl('info.ListNouvelle') %>" ActCss="active" IActCss="" />
                        <com:AxListMenuItem Text="Aventures" PagePath="aventures.ListAventure" 
                            NavigateUrl="<%= $this->Service->constructUrl('aventures.ListAventure') %>" ActCss="active" IActCss="" />
                        <com:AxListMenuItem Text="Gestion" PagePath="admin.Gestion" 
                            NavigateUrl="<%= $this->Service->constructUrl('admin.Gestion') %>" ActCss="active" IActCss=""
                            Visible="<%= !$this->User->IsGuest && $this->User->IsAdmin %>" />
                        <com:AxListMenuItem Text="Marché" PagePath="market.ListShop" 
                            NavigateUrl="<%= $this->Service->constructUrl('market.ListShop') %>" ActCss="active" IActCss=""
                            Visible="<%= !$this->User->IsGuest %>" />
                    </com:Application.Common.AxListMenu>
                </div>
             
                <div class="header">
                </div>
                
                <div class="main">
                
                    <div id="sidebar" class="leftside">
                    
                    	<div id="UserDiv" class="side">
                            <com:Application.Portlets.LoginPortlet Visible="<%= $this->User->IsGuest && $this->Page->Title != 'Nouvel utilisateur' %>" />
                            <com:Application.Portlets.AccountPortlet Visible="<%= !$this->User->IsGuest %>" />       
						</div>
                        
                        <div id="ActionDiv" class="side">
							<com:TContentPlaceHolder ID="sidebar"/>
						</div>
                        
                    </div>
                    
                    <div id="content" class="rightside">
                        <com:TContentPlaceHolder ID="Main" />
                    </div>
                </div>               
                
                <br/>
                <div class="footer">
                    <%= PRADO::poweredByPrado() %> <com:TLabel ID="Copyright" Text="&copy; 2010 - <%= date('Y') %>" />
                </div>            
             
            </div>
        </com:TForm>
    </body>
</html> 

