var app = new Vue({
  el: '#app',
  data: {
    searchInput: "",
    sites: [],
    currentSiteCounter: 0,


    siteCounterStep: 20,
    siteCounterBase: 0,
  },
  methods: {

    /**
     * On page load, perform the following actions
     */
    init: function(){
      let self = this;
      let hash = window.location.hash.substring(1);
      self.performSearch(hash);
      self.searchInput = hash;
    }, 

    /**
     * Event listener for searching based on text input
     * @param  {Event} event 
     */
  	search: function(event){
      let self = this;

  		event.preventDefault();
      self.currentSiteCounter = 0;

  		if( self.searchInput !== "" ){
	  		self.performSearch(self.searchInput);
        history.pushState({}, null, "#"+self.searchInput);
  		}
  	},

    /**
     * Send API requests to search tags and retrieve site info
     * @param  {string} input User input string from text field
     */
    performSearch: function(input){
      let self = this;

      $.ajax({
          url: "/api/search/" + input,
          method: "GET",
          dataType: "json",
          success: function(result){
            let incSites = [];

            if( result.data.length == 0 ){
              self.sites = [];
            } else {
              $.each(result.data, function(key, value){
                incSites.push(key);
              });

              // Create comma-separated list of incoming site IDs
              let siteIDs = incSites.join();

              $.ajax({
                url: "/api/get_site_info/" + siteIDs,
                method: "GET",
                dataType: "json",
                success: function(result){
                  self.sites = result.data;
                }
              });
            }

          }
      });
    },

    getS3Image: function(url){
      var domain = url.replace('http://', "");
      domain = url.replace('https://', "");
      domain = domain.replace("/", "");
      console.log(domain);
      return "http://granite-eb-simplesitesearch.s3.amazonaws.com/site_images/"+ domain +".png";
    }
  }
})

app.init();