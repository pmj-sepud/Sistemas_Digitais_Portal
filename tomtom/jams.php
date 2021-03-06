<?
session_start();
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

//logger("Acesso","Logs");
?>
<style src="https://api.tomtom.com/maps-sdk-for-web/5.x/5.38.0/examples/sdk/web-sdk-maps/maps.css"></style>
<style src="https://api.tomtom.com/maps-sdk-for-web/5.x/5.38.0/examples/pages/examples/styles/main.css"></style>
<style src="https://api.tomtom.com/maps-sdk-for-web/5.x/5.38.0/examples/sdk/web-sdk-maps/css-styles/traffic-incidents.css"></style>
<section role="main" class="content-body">
      <header class="page-header">
          	<h2>TOMTOM Developer área</h2>
          	<div class="right-wrapper pull-right" style='margin-right:15px;'>
          		<ol class="breadcrumbs">
          			<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          		</ol>
          	</div>
      </header>
      <section class="panel">
        		<div class="panel-body box_shadow">

                <div id='map' class='map' style="width:100%;height:550px">
                      <div class='tt-overlay-panel -left-top -medium js-foldable'>
                          <div class='tt-form'>
                              <label class='tt-form-label tt-spacing-top-24'>Traffic incidents style
                                  <select class='js-style-select tt-select'>
                                      <option value='s1'>s1</option>
                                      <option value='s2'>s2</option>
                                      <option value='s3'>s3</option>
                                  </select>
                              </label>
                          </div>
                      </div>
               </div>

                <script>
//  key: 'QVAKRTufPJhvP8erGPEjWs50VbAnd5fG',

///////////////////////////////////////////////////////////////////////
/**
 * @description Makes the element foldable.
 * @param {String} selector Element selector (any valid CSS selector).
 * @param {String="top-right"} position Position of the fold button.
 */
function Foldable(selector, position) {
    this.position = position;
    this.element = document.querySelector(selector);
    this.element.classList.add('tt-foldable');
    this.foldButton = this._createFoldButton();
    this.isFolded = false;
    this.overflowTimeout;

    this._addFoldButton();
    this._bindEvents();
}

Foldable.prototype._createFoldButton = function() {
    var foldButton = document.createElement('button');
    foldButton.setAttribute('class', 'tt-foldable__button -' + this.position);

    return foldButton;
};

Foldable.prototype._addFoldButton = function() {
    this.element.appendChild(this.foldButton);
};

Foldable.prototype._bindEvents = function() {
    this.foldButton.addEventListener('click', this._toggleFold.bind(this));
};

Foldable.prototype._toggleFold = function() {
    this.element.classList.toggle('-folded');

    if (!this.isFolded) {
        this.element.classList.add('-open');
    }

    window.clearTimeout(this.overflowTimeout);

    if (this.isFolded) {
        this.overflowTimeout = window.setTimeout(function() {
            this.element.classList.remove('-open');
        }.bind(this), 200);
    }

    this.isFolded = !this.isFolded;
};

window.Foldable = window.Foldable || Foldable;

// function copied from http://detectmobilebrowsers.com/
function isMobileOrTablet() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
}

window.isMobileOrTablet = window.isMobileOrTablet || isMobileOrTablet;

///////////////////////////////////////////////////////////////////////

var baseStyle = 'tomtom://vector/1/';
var incidentConfig = {
key: 'QVAKRTufPJhvP8erGPEjWs50VbAnd5fG',
incidentDetails:  {
diff: true,
style: 's2'
},
incidentTiles: {
style: baseStyle + 's2',
},
refresh: 30000
};
var map = tt.map({
key: 'QVAKRTufPJhvP8erGPEjWs50VbAnd5fG',
container: 'map',
style: baseStyle + 'basic-main',
center: [-48.840862,-26.301033],
zoom: 12,
dragPan: !window.isMobileOrTablet()
});
map.addControl(new tt.FullscreenControl());
map.addControl(new tt.NavigationControl());


new Foldable('.js-foldable', 'top-right');
var styleSelect = tail.select('.js-style-select', {
classNames: 'tt-fake-select',
hideSelected: true
});


function updateLayer()
{
  return map.addTier(new tt.TrafficIncidentTier(incidentConfig));
}

map.once('load', function(){
    styleSelect.on('change', function(event)
    {
        alert("Alterou o estilo");
        incidentConfig.incidentTiles.style = baseStyle + event.value;
        incidentConfig.incidentDetails.style = event.value;
        map.removeTier('trafficIncidents').then(updateLayer);
    });
updateLayer();
});

                </script>

            </div>
      </section>
</section>
<script>

</script>
