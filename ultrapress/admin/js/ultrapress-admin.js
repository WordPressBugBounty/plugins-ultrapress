(function( $ ) {
$( document ).ready( function() {
// temporary circuit. used until circuits loading process finish
var arrcircuit  = { 'arch': {
        'test': {
            x: 4000,
            y: 4000, 
            path: '',
            disc: '',
            trigger_of_component: '',
            name: '',
            outputConnectedToThisCirc: '',
            input: {},
            outputs: {},
        },
    },
};

var ultrapress_comp = Vue.component('ultrapress-comp', {
    template: '#ultrapress-template',  // look in ultrapress-admin-display.php
    data() {
        return {
            circuits: [], // array of circuits
            packages: [], // array of circuits
            package_of_plugin: '', // package of new plugin
            new_name_of_package_for_circuit: '', // name of package that you want to add the circuit to
            create_or_update: 'create', // create or update the package
            package_to_be_cloned: '', // name of package to be cloned
            circuitsOfPackage: [], // array of circuits of new package
            componentsOfPackage: [], // array of components of new package
            nameOfPackage: '', // name of new package
            descOfPackage: '', // description of new package
            composedCompsOfCircuit: [], // array of composed components of exported circuit
            /////mapPaths: [],
            /////keyOfAddedComponent: '',
            currentCircuitKey: '', // key of current circuit
            newCompKey: '', // key of component to be added to current circuit
            newTrigKey: '', // trigger to be added to current circuit
            newActivateCompKey: '', // key of new Activate Comp
            newDeactivateCompKey: '', // key of new deActivate Comp
            newCompX: 0, // x position of new created comp
            newCompY: 0, // y position of new created comp
            components: [], // array of components
            triggers: [], //  // array of triggers
            newCircInfo: {}, // object containing new Circuit Info
            composedCompInfo: {}, // object containing new composed component Info
            args_for_execute: {}, // args passed to executed circuit
            newArgNode: {},  // object containing new arg for node component
            width: 1000, // width of svg
            height: 600, // height of svg
            originX: 0, // x origin   of svg
            originY: 0, // x origin   of svg
            zoom: 1,  // scale of svg
            InCircle:  data = {    // to style svg
                'r' : 25,
                'fill' : 'green',
                'stroke' : '#82b366',
            },
            data_loaded: false,   // flag: true when data loading finish
            /////exploreFlag: false,
            currentComposedCircuit: {},
            showCirc: false,  
            currentClickedOutPut: false, // current Clicked OutPut
            currentCompKey: '', // key of current Component
            currentOutputKeys: false,  // key of current Clicked Output
            currentArgKey: false, // current Arg to be edited
            currentTrigArgKey: '', // current Trigger Arg to be edited
            currentTrig: '', // current Trigger  to be edited
            currentActivateArg: '', // current Activate Arg to be edited
            currentActivateKey: '', // index of current Activate  to be edited
            currentDeactivateArg: '', // current deActivate Arg to be edited
            currentDeactivateKey: '', // index of current deActivate  to be edited
            key_of_comp_to_delete: '', // store key of component to be deleted
            ajaxable: 0, // is current circuit ajaxable?
            argOptions: {},
            argOptionsTrig: {},
            argOptionsActivate: {},
            argOptionsDeactivate: {},
            typeOfMap: ['map', 'literal', 'function', 'eval'],  // used to edit arguments
            typeOfMapWithDefault: ['default', 'map', 'literal', 'function', 'eval'],  // used to edit arguments
        }
    },
    computed: {
        // current clicked output
        currentOutput() {
            if (this.currentOutputKeys && this.currentCircuit.arch[this.currentOutputKeys[0]]) {
                out  = this.currentCircuit.arch[this.currentOutputKeys[0]].outputs[this.currentOutputKeys[1]];
                return out
            } else {
                return false
            }
            
        },        

        // key of component Connected To current Output
        componentConnectedTocurrentOutput() {
            if (this.currentOutput) {
                return this.currentOutput.idOfConnection; 

            } else {
                return false
            }         
        },
        // viewBox of svg
        viewBox() {
            return   this.originX + ' ' + this.originY  + ' ' + this.width + ' ' + this.height;
        },
        // values of select arguments
        values_of_select_argument() {
            if (this.currentCircuit.arch[this.componentConnectedTocurrentOutput].input[this.currentArgKey]) {
               return this.currentCircuit.arch[this.componentConnectedTocurrentOutput].input[this.currentArgKey].select_values;
            } 
        },

        // current edites Argument
        currentArg() {
            if (   this.componentConnectedTocurrentOutput 
                && this.currentArgKey 
                && this.currentCircuit.arch[this.componentConnectedTocurrentOutput] 
                && this.currentCircuit.arch[this.componentConnectedTocurrentOutput].input 
                && this.currentCircuit.arch[this.componentConnectedTocurrentOutput].input[this.currentArgKey]) {

                return   this.currentCircuit.arch[this.componentConnectedTocurrentOutput].input[this.currentArgKey];
            } else {
                return false
            }
        },

        // current Circuit
        currentCircuit() {
            if (this.currentCircuitKey && this.exploreFlag) {
                return this.currentComposedCircuit;
            }

            if (this.currentCircuitKey) {
                c = this.circuits[this.currentCircuitKey];

                // preprocessing of data in circuit
                Object.keys(c.arch).forEach(function(key) {
                    c.arch[key].x = Number(c.arch[key].x);
                    c.arch[key].y = Number(c.arch[key].y);
                    if ( (!c.arch[key].input) 
                        || Array.isArray(c.arch[key].input) )  {
                        c.arch[key].input = {};
                    }
                });

                // preset data of circuit
                this.currentCompKey = ''; 
                this.currentClickedOutPut = false;
                this.currentOutputKeys= false;
                this.currentArgKey = false;
                this.argOptions = {};
                this.argOptionsTrig = {};

                // args passed to executed circuit
                this.args_for_execute = JSON.parse(JSON.stringify( c.arch[c.id_of_first_component].input));

                // create/reset missing data of circuit
                if (! c.triggers) {
                    c.triggers = {};
                } 
                if (! c.activate) {
                    c.activate = {};
                }
                if (! c.deactivate) {
                    c.deactivate = {};
                }
                if (! c.ajaxable || c.ajaxable == 0) {
                    c.ajaxable = false; 
                } else {
                    c.ajaxable = true; 
                }
                return c
            } else {
                return arrcircuit
            }
        },
    },

    methods: {
    /**  
     * string path
     * x,y: coordonne of origin
    */
    url_of_component(comp, icon_url){
        if (! (comp && comp.trigger_of_component && (this.components[ comp.trigger_of_component ])) ) {
            return '';
        }
        return  this.components[ comp.trigger_of_component ].url_of_component_dir + icon_url;
    },
    /**  
     * string path
     * x,y: coordonne of origin
    */
    headPath(x,y){
      return 'M' + x + ' ' + y + ' L' + x + ' ' + (y + 100);
    },

    /**  
     * string path of output
     * x,y: coordonne of origin
     * n: number of outputs
     * i: output rect coordonne
    */
    outputPath(x ,y, n, i){
      destinX  =  this.outputsCalculX(x, y, n, i) + 50;
      return 'M' + x + ' ' + (y + 250) + ' L' + destinX + ' ' + (y + 325);
    },

    /**  
     * string path from output To Trigger
     * x,y: coordonne of origin
     * n: number of outputs
     * i: output rect coordonne
    */
    outputPathToTrigger(x ,y, n, i){
      destinX  =  this.outputsCalculX(x, y, n, i) + 50;
      return 'M' + destinX + ' ' + (y + 425) + ' L' + destinX + ' ' + (y + 500);
    },

    /**  
     * compute coordonne of outputs rects
     * x,y: coordonne of origin
     * n: number of outputs
     * i: output rect coordonne
    */
    outputsCalculX(x, y, n, i){
        // width of all rects and spaces between them
        w  =  100*n + 80*(n - 1);

        // origin of most lefr rect
        leftX  =  x - (w/2);

        if (0 == i) {
            return leftX
        } 
        else {
            return leftX +  100*i + 80*i;
        }
    },

    /**  
     * path that connect current output with next component 
     * x,y: coordonne of origin
     * n: number of outputs
     * i: output rect coordonne
     * conn: key of component connected to current output
    */
    connectCircuit(x, y, n, i, conn){
        if (!this.currentCircuit.arch[conn]) {
            return '';
        }
        X0  =  this.outputsCalculX(x, y, n, i) + 50;
        destinX  = this.currentCircuit.arch[conn].x;
        destinY  = this.currentCircuit.arch[conn].y;
        return 'M' + X0 + ' ' + (y + 500) + ' C '  + (X0 + destinX)/2 + ' ' + (destinY + y + 500)/2.1 + ', '
         + (X0 + destinX)/2 + ' ' + (destinY + y + 500)/1.9 + ', ' + destinX + ' ' + destinY;      
    },
    
    // show toast
    show_toast(message, isSuccess = true) {
        if (isSuccess) {
            $( '#header-toast-ultrapress' ).removeClass( 'bg-danger' ).addClass( 'bg-success' );
        } else {
            $( '#header-toast-ultrapress' ).removeClass( 'bg-success' ).addClass( 'bg-danger' );
        }

        $( '#text-toast-ultrapress' ).text( message );
        $('.toast').toast('show');
    },
    /**  
     * save current circuit 
    */
    save(){
        if (this.currentCircuit.ajaxable) {
                this.currentCircuit.ajaxable = 1;
        } else {
            this.currentCircuit.ajaxable = 0;
        }

        var $button = $('#save-circuit');

        var data = {
            'action' : 'save_ultrapress',
            'circuit' : this.currentCircuit,
            'circuit_key' : this.currentCircuitKey,
            'ultrapress_nonce': $button.data('nonce'),
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {  
                $button.prop('disabled', false);  

                if (data.response == "F") {
                    this.show_toast("circuit not saved", false);    
                } else {
                    this.show_toast("circuit saved successfully", true);
                }                
            },
            error: (data,status,error) => {
                $button.prop('disabled', false);
                this.show_toast("circuit not saved", false); 
            },
        });    
    },

    modalNewDeactivate(){
        $( "#dialog-new-deactivate" ).dialog( "open" ); 
    },

    addNewDeactivate(){
        deactivateOfCurrentCircuit = this.currentCircuit.deactivate;
        newCompKey = this.newDeactivateCompKey;
        comp = this.components[newCompKey];

        args_options = {};

        inputs = comp.input;

        Object.keys(inputs).forEach(function(key) {
            args_options[key] = {
                            'type_of_map' : 'map',
                            'map' : '',
                         };
                });

        var act = {
            'trigger_of_component' : newCompKey,
            'args_options' : args_options,
         };

        this.currentCircuit.deactivate[newCompKey] = args_options;

        this.originX = this.originX + 1;
        this.originX = this.originX - 1; 
        this.newDeactivateCompKey = "";
        $( "#dialog-new-deactivate" ).dialog( "close" ); 
    },

    editDeactivateArg(keyArg, keyComp){ 
        this.currentDeactivateKey = keyComp;
        this.currentDeactivateArg = keyArg;
        
        mapping = this.currentCircuit.deactivate[keyComp][keyArg];
        this.argOptionsDeactivate = {};

        if ( mapping && mapping.map) {
            this.argOptionsDeactivate["type_of_map"] = mapping.type_of_map;
            this.argOptionsDeactivate["map"] = mapping.map;
        }

        $( "#dialog-edit-deactivate-arg" ).dialog( "open" ); 
 
    },

    saveArgOptionsDeactivate(){ 
        if (!( this.argOptionsDeactivate.map && 
            this.argOptionsDeactivate.type_of_map) ) {
            return
        }
        this.currentCircuit.deactivate[this.currentDeactivateKey][this.currentDeactivateArg ] = this.argOptionsDeactivate;

        $( "#dialog-edit-deactivate-arg" ).dialog( "close" ); 
        this.currentDeactivateArg = "";
    },

    deleteDeactivate(keycomp){ 
        delete this.currentCircuit.deactivate[keycomp]; 
        this.originX = this.originX + 1;
        this.originX = this.originX - 1; 
    },

    modalNewActivate(){
        $( "#dialog-new-activate" ).dialog( "open" ); 
    },

     addNewActivate(){
        activateOfCurrentCircuit = this.currentCircuit.activate;
        newCompKey = this.newActivateCompKey;
        comp = this.components[newCompKey];

        args_options = {};

        inputs = comp.input;

        Object.keys(inputs).forEach(function(key) {
            args_options[key] = {
                            'type_of_map' : 'map',
                            'map' : '',
                         };
                });

        this.currentCircuit.activate[newCompKey] = args_options;

        this.originX = this.originX + 1; // to update svg
        this.originX = this.originX - 1; 
        this.newActivateCompKey = "";
        $( "#dialog-new-activate" ).dialog( "close" ); 
    },

    deleteComponentDialog(key){ 
        this.key_of_comp_to_delete = key;

        $( "#dialog-delete-component" ).dialog( "open" ); 
    },

    deleteComponent(){ 
        delete this.currentCircuit.arch[this.key_of_comp_to_delete];
        this.currentCompKey = "";

        this.originX = this.originX + 1;
        this.originX = this.originX - 1; 
        $( "#dialog-delete-component" ).dialog( "close" ); 
    },

    deleteActivate(indComp){      
        delete this.currentCircuit.activate[indComp]; 
        this.originX = this.originX + 1;
        this.originX = this.originX - 1; 
 
    },

    editActivateArg(keyArg, keyComp){ 
        this.currentActivateKey = keyComp;
        this.currentActivateArg = keyArg;
        
        mapping = this.currentCircuit.activate[keyComp][keyArg];
        this.argOptionsActivate = {};

        if ( mapping && mapping.map) {
            this.argOptionsActivate["type_of_map"] = mapping.type_of_map;
            this.argOptionsActivate["map"] = mapping.map;
        }

        $( "#dialog-edit-activate-arg" ).dialog( "open" ); 
    },

    saveArgOptionsActivate(){ 
        if (!( this.argOptionsActivate.map && 
            this.argOptionsActivate.type_of_map) ) {
            return
        }
        this.currentCircuit.activate[this.currentActivateKey][this.currentActivateArg ] = this.argOptionsActivate;

        $( "#dialog-edit-activate-arg" ).dialog( "close" ); 
        this.currentActivateArg = "";
 
    },

    deleteTrig(keyTrig){ 
        triggers = this.currentCircuit.triggers;
        delete triggers[keyTrig]; 

        this.originX = this.originX + 1;
        this.originX = this.originX - 1; 
    },

    modalRun(){
        $( "#dialog-run" ).dialog( "open" ); 
    },

    run(){ 
        args = {};
        Object.keys(this.args_for_execute).forEach(function(key) {
            args[key] = this.args_for_execute[key].value;
        }.bind(this));

        var $button = $('#run-circuit');
        var data = {
            'action' : 'run_circuit_ultrapress',
            'trigger_of_circuit' : this.currentCircuitKey,
            'ultrapress_nonce': $button.data('nonce'),
            'args' : args,
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {    
                if (data.response == "F") {
                  console.log(data.error_message);    
                }  
                $button.prop('disabled', false);

            },
            error: (data,status,error) => {
                $button.prop('disabled', false);
            },
        }); 
    },

    modalNewTrig(){
        $( "#dialog-new-trigger" ).dialog( "open" ); 
    },

    addNewTrig(){
        triggersOfCurrentCircuit = this.currentCircuit.triggers;
        newTriggerKey = this.newTrigKey;
        triggerElement = this.triggers[newTriggerKey];

        args_options = {};

        comp = this.currentCircuit.arch[ this.currentCircuit.id_of_first_component ];
        inputs = comp.input;

        Object.keys(inputs).forEach(function(key) {
            args_options[key] = {
                            'type_of_map' : 'map',
                            'map' : '',
                         };
                });

        var trigger = {
            'disc' : triggerElement.disc,
            'args' : triggerElement.args,
            'additional_args' : triggerElement.additional_args,
            'args_options' : args_options,
         };
         
        this.currentCircuit.triggers[newTriggerKey] = trigger;

        this.originX = this.originX + 1;
        this.originX = this.originX - 1; 
        $( "#dialog-new-trigger" ).dialog( "close" ); 
    },

    editTrig(keyInp, keyTrig){ 
        this.currentTrig = keyTrig;
        this.currentTrigArgKey = keyInp;
        mapping = this.currentCircuit.triggers[keyTrig].args_options[keyInp];
        this.argOptionsTrig = {};

        if ( mapping && mapping.map) {
            this.argOptionsTrig["type_of_map"] = mapping.type_of_map;
            this.argOptionsTrig["map"] = mapping.map;
        }

        $( "#dialog-edit-trig" ).dialog( "open" ); 

    },

    saveArgOptionsTrig(){ 
        if (!( this.argOptionsTrig.map && this.argOptionsTrig.type_of_map) ) {
            return
        }
        this.currentCircuit.triggers[this.currentTrig].args_options[this.currentTrigArgKey ] = this.argOptionsTrig;

        $( "#dialog-edit-trig" ).dialog( "close" ); 
        this.currentTrigArgKey = false;
    },

    editArg(keyInp, inp){ 
        this.currentArgKey = keyInp;
        mapping = this.currentOutput.args_options[keyInp];
        this.argOptions = {};

        if ( mapping && mapping.type_of_map) {
            this.argOptions["type_of_map"] = mapping.type_of_map;
            this.argOptions["map"] = mapping.map;
        }


        $( "#dialog-edit-arg" ).dialog( "open" ); 
    },

    saveArgOption(){ 
        if (!( this.argOptions.map && 
            this.argOptions.type_of_map) ) {
            return
        }
        this.currentOutput.args_options[this.currentArgKey] = this.argOptions;
        $( "#dialog-edit-arg" ).dialog( "close" ); 
        this.currentArgKey = false; 
    },

    changeExportedArg(key){ 
        // Component Connected To Current Output
        comp  = this.currentCircuit.arch[this.currentOutput.idOfConnection];
        args = this.currentOutput.args;

        if (this.currentOutput.args[key].exported) {
            this.currentOutput.args[key].exported = 1;
            if (comp) {
                if (!comp.exported) {
                    comp.exported = {};
                }
                comp.exported[key] = {
                    'key' : key,
                };
                exportedArg = JSON.parse(JSON.stringify(this.currentOutput.args[key]));

                // outputs Of Component Connected To Current Output
                outputs = this.currentCircuit.arch[this.currentOutput.idOfConnection].outputs;

                Object.keys(outputs).forEach(function(keyout) {
                    k  =  JSON.parse(JSON.stringify(this.currentOutput.args[key]));
                    k.exported = 0;
                    if (!outputs[keyout].args) {
                        outputs[keyout].args = {};
                    }
                    outputs[keyout].args["_exp_" + key] = k;
                }.bind(this));
            }                        
        } else {
            this.currentOutput.args[key].exported = 0;
            if (comp) {
                if (!comp.exported) {
                    comp.exported = {};
                }
                delete comp.exported[key];

                outputs = this.currentCircuit.arch[this.currentOutput.idOfConnection].outputs;
                Object.keys(outputs).forEach(function(keyout) {
                    delete outputs[keyout].args["_exp_" + key];
                }.bind(this));
            }            
        }
    },

    addComposedCompInfo(){ 
        $( "#dialog-export-composed-comp" ).dialog( "open" ); 
    },

    exportComposedComp(){
    if ( ! this.composedCompInfo.trigger ) {
        $( '#composed_comp_info_trigger' ).css( 'border', '1px solid red' );
        return 0;
    } else {
        $( '#composed_comp_info_trigger' ).css( 'border', '1px solid green' );
    }

    if ( ! this.composedCompInfo.name ) {
        $( '#composed_comp_info_name' ).css( 'border', '1px solid red' );
        return 0;
    } else {
        $( '#composed_comp_info_name' ).css( 'border', '1px solid green' );
    }

    var $button = $('#export-composed-component');

    var data = {
            'action' : 'export_composed_comp_ultrapress',
            'circuit_key' : this.currentCircuitKey,
            'disc' : this.composedCompInfo.disc,
            'trigger' : this.composedCompInfo.trigger,
            'name' : this.composedCompInfo.name,
            'ultrapress_nonce': $button.data('nonce'),
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {  
                $button.prop('disabled', false);  
                if (data.response == "F") {
                    this.show_toast(data.message, false);   
                } else {
                    this.show_toast("composed component saved successfully ", true);
                }                    
            },
            error: (data,status,error) => {
                $button.prop('disabled', false);
                this.show_toast("error: composed component not saved", false);
            },
        });  

    $( "#dialog-export-composed-comp" ).dialog( "close" ); 

    },

    addNewArgNodeInfo(){ 
        this.newArgNode = {
            "name": "",
            "disc": "",
        }

        $( "#dialog-new-arg-node" ).dialog( "open" ); 
    },

    deleteArgNode(keyInp){ 
        input = this.currentCircuit.arch[this.currentCompKey].input;
        delete input[keyInp]; 

        outputs  = this.currentCircuit.arch[this.currentCompKey].outputs;
        Object.keys(outputs).forEach(function(key) {
            if (! outputs[key].args) {
                outputs[key].args = {};
            }
            delete outputs[key].args[keyInp];           
                }.bind(this));

        this.originX = this.originX + 1;
        this.originX = this.originX - 1; 
    },

    addNewArgNode(){ 
        if (!( 
            this.newArgNode.name &&  
            this.newArgNode.disc) ) {
            return
        }

        if (! this.currentCircuit.arch[this.currentCompKey].input) {
            this.currentCircuit.arch[this.currentCompKey].input = {};
        }
        this.currentCircuit.arch[this.currentCompKey].input[this.newArgNode.name] = {
            description: this.newArgNode.disc,
            primal: 0,
            type_of_variable: 0,
            name: this.newArgNode.name,
            required: 0,
        };
        
        outputs  = this.currentCircuit.arch[this.currentCompKey].outputs;
        Object.keys(outputs).forEach(function(key) {
            if ((! outputs[key].args) || Array.isArray(outputs[key].args)) {
                outputs[key].args = {};
            }
            outputs[key].args[this.newArgNode.name] = {
                description: this.newArgNode.disc,
                type_of_variable: 0,
                name: this.newArgNode.name,
                exported: 0,
            };           
            
        }.bind(this));

        this.originX = this.originX + 1;
        this.originX = this.originX - 1;
        this.newArgNode.name = "";
        this.newArgNode.disc = "";
        $( "#dialog-new-arg-node" ).dialog( "close" ); 
    },

    addNewCircInfo(){ 
        $( "#dialog-new-circuit" ).dialog( "open" ); 
    },

    addNewCirc(){ 
        if ( ! this.newCircInfo.trigger_of_circuit ) {
            $( '#trigger_of_new_circuit' ).css( 'border', '1px solid red' );
            return 0;
        } else {
            $( '#trigger_of_new_circuit' ).css( 'border', '1px solid green' );
        }

        if ( ! this.newCircInfo.first_trigger ) {
            $( '#first_trigger_of_new_circuit' ).css( 'border', '1px solid red' );
            return 0;
        } else {
            $( '#first_trigger_of_new_circuit' ).css( 'border', '1px solid green' );
        }


        // generate random key for first component of new circuit
        randomKey = "ultra-" +  Math.floor(Math.random() * 10000000000000000);
        newCirc = {
            disc: this.newCircInfo.disc,
            trigger_of_circuit: this.newCircInfo.trigger_of_circuit,
            first_trigger: this.newCircInfo.first_trigger ,
            id_of_first_component: randomKey,
            component: "",
            activate: {},
            Deactivate: {},
            "arch": {},
        };
        

        targetedComp = this.components[ this.newCircInfo.first_trigger ];
        var newComp = JSON.parse(JSON.stringify(targetedComp));

        newComp.path = this.newCircInfo.trigger_of_circuit + "|" + this.newCircInfo.first_trigger;

        newComp.x = 300;
        newComp.y = 200;

        // if first component is a node component
        if ('ultra/node' == newComp.trigger_of_component) {
            newComp.input = {};
            newComp.additional_input = {};
            newComp.outputs['/out_1'].args = {};
            newComp.outputs['/out_2'].args = {};
            newComp.outputs['/out_1'].args_options = {};
            newComp.outputs['/out_2'].args_options = {};
        }

        newCirc.arch[ randomKey ] = newComp;

        this.circuits[ this.newCircInfo.trigger_of_circuit ] = newCirc;
        this.currentCircuitKey = this.newCircInfo.trigger_of_circuit;

        this.originX = 0;
        this.originY = 0;

        $( "#dialog-new-circuit" ).dialog( "close" ); 
    },

    modalPackage(){
        this.circuitsOfPackage = [];
        this.componentsOfPackage = [];
        this.nameOfPackage = ""; 
        this.descOfPackage = "";
        this.package_to_be_cloned = "";

        $( "#dialog-package" ).dialog( "open" ); 
    },
    createPackage(){
        var $button = $('#create-package');
        if ( ! this.nameOfPackage ) {
            $( '#name-of-new-package' ).css( 'border', '1px solid red' );
            return 0;
        } else {
            var regexp = /^[a-zA-Z0-9-_]+$/;
            var check = this.nameOfPackage;
            if (check.search(regexp) === -1)
                { 
                    $( '#name-of-new-package' ).css( 'border', '1px solid red' );
                    return false;
                 }
            $( '#project-title' ).css( 'border', '1px solid green' );
        }

        var data = {
            'action' : 'create_package_ultrapress',
            'name_of_package' : this.nameOfPackage ,
            'desc_of_package' : this.descOfPackage ,
            'ultrapress_nonce': $button.data('nonce'), 
         };
        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                $button.prop('disabled', false);
                if (data.response == "F") {
                    this.show_toast("error: package not created", false); 
                } else {
                    this.show_toast("package created successfully ", true);
                }
            },
            error: (data,status,error) => {
                $button.prop('disabled', false);
                this.show_toast("error: package not created", false); 
            },
        }); 

        $( "#dialog-package" ).dialog( "close" ); 
    },
    clonePackage(){
        var $button = $('#clone-package');

        if ( ! this.nameOfPackage ) {
            $( '#name-of-new-package' ).css( 'border', '1px solid red' );
            return 0;
        } else {
            var regexp = /^[a-zA-Z0-9-_]+$/;
            var check = this.nameOfPackage;
            if (check.search(regexp) === -1)
                { 
                    $( '#name-of-new-package' ).css( 'border', '1px solid red' );
                    return false;
                 }
                 $( '#name-of-new-package' ).css( 'border', '1px solid green' );
        }

        if (! this.package_to_be_cloned) {
            $( 'name-of-package-to-clone' ).css( 'border', '1px solid red' );
            return false;
        } else {
            $( '#name-of-package-to-clone' ).css( 'border', '1px solid green' );
        }

        var data = {
            'action' : 'clone_package_ultrapress',
            'name_of_package' : this.nameOfPackage ,
            'desc_of_package' : this.descOfPackage ,
            'package_to_be_cloned' : this.package_to_be_cloned ,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                $button.prop('disabled', false);
                if (data.response == "F") {
                    this.show_toast("error: package not cloned", false);
                } else {
                    this.show_toast("package cloned successfully ", true);
                }
            },
            error: (data,status,error) => {
                $button.prop('disabled', false);
                this.show_toast("error: package not cloned", false);
            },
        }); 

        $( "#dialog-package" ).dialog( "close" ); 
    },

    modalPlugin(){
        this.package_of_plugin = "";

        $( "#dialog-plugin" ).dialog( "open" ); 
    },

    createPlugin(){
        var $button = $('#create-plugin');

        if (! this.package_of_plugin) {
            $( '#name-of-package-of-plugin' ).css( 'border', '1px solid red' );
            return false;
        } else {
            $( '#name-of-package-of-plugin' ).css( 'border', '1px solid green' );
        }
        var data = {
            'action' : 'create_plugin_ultrapress',
            'name_of_package_of_plugin' : this.package_of_plugin ,
            'ultrapress_nonce': $button.data('nonce'), 
         };
        console.log(data);
        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                $button.prop('disabled', false);
                console.log(data);
                if (data.response == "F") {
                  this.show_toast("error: plugin not saved", false);   

                } else {
                   this.show_toast("plugin saved successfully ", true);
                }

            },
            error: (data,status,error) => {
                $button.prop('disabled', false);
                console.log(data);
                console.log(error);
                this.show_toast("error: plugin not saved", false);
            },
        }); 

        $( "#dialog-plugin" ).dialog( "close" ); 
    },

    modalNewComp(e){
        e.stopPropagation();
        x = e.clientX;
        y = e.clientY;
        this.newCompX = x;
        this.newCompY = y;

        $( "#dialog" ).dialog( "open" ); 
    },

    addNewComp(e){
        if (! this.newCompKey) {
            return 0;
        };

        targetedComp = this.components[ this.newCompKey ];
        var newComp = JSON.parse(JSON.stringify(targetedComp));
        newComp.x = this.zoom * this.newCompX + this.originX;
        newComp.y = this.zoom * this.newCompY + this.originY; 

        // if the new component is a node
        if ('ultra/node' == newComp.trigger_of_component) {
            newComp.input = {};
            newComp.additional_input = {};
            newComp.outputs['/out_1'].args = {};
            newComp.outputs['/out_2'].args = {};
            newComp.outputs['/out_1'].args_options = {};
            newComp.outputs['/out_2'].args_options = {};
        }

        // generate a random key for the new component
        randomKey = "ultra-" +  Math.floor(Math.random() * 10000000000000000);
        this.circuits[ this.currentCircuitKey ].arch[ randomKey ] = newComp;
        tmp = this.currentCircuitKey;
        this.currentCircuitKey = "";
        this.newCompKey = "";
        this.currentCircuitKey = tmp;

        $( "#dialog" ).dialog( "close" ); 
    },
    modal_add_circuit_to_package(){
        this.new_name_of_package_for_circuit = "";
        $( "#dialog-update-package" ).dialog( "open" ); 
    },
    add_circuit_to_package(){
        var $button = $('#add_circuit_to_package');
        var data = {
            'action' : 'add_circuit_to_package_ultrapress',
            'circuit_key' : this.currentCircuitKey,
            'new_name_of_package_for_circuit' : this.new_name_of_package_for_circuit,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                $button.prop('disabled', false);
                if (data.response == "F") {
                    this.show_toast("error: package not updated", false);  
                } else {
                    this.show_toast("package updated successfully", true);
                    location.reload();
                }
            },
            error: (data,status,error) => {
                $button.prop('disabled', false);
                this.show_toast("error: package not updated", false); 
            },
        });     

        $( "#dialog-update-package" ).dialog( "close" ); 
    },

    clickOnOut(keyComp, keyout){
        this.currentClickedOutPut = [keyComp, keyout] ;
    },

    dbclickOnOut(keycirc, keyout, e){
        e.stopPropagation();
        from  = this.currentCircuit.arch[keycirc].outputs[keyout];
        
        if (from.idOfConnection) {
            comp  = this.currentCircuit.arch[from.idOfConnection];
            if (comp) {
                auto_fill_of_args = comp.auto_fill_of_args;
                if ( auto_fill_of_args == 1 ) {
                    Object.keys(comp.outputs).forEach(function(key) {
                        comp.outputs[key].args = {};
                    }.bind(this));
                }
                // to update svg
                this.originX = this.originX + 1;
                this.originX = this.originX - 1;
            }

            if (! from.args) {
                from.args = {};
            }

            Object.keys(from.args).forEach(function(key) {
                if (from.args[key].exported) {
                    if (!comp.exported) {
                        comp.exported = {};
                    }
                    delete comp.exported[key];

                    outputs = comp.outputs;
                    Object.keys(outputs).forEach(function(keyout) {
                        delete outputs[keyout].args["_exp_" + key];
                    }.bind(this));
                }

            }.bind(this));         
        }


        
        from.idOfConnection = '';
        from.connection = '';

        this.currentClickedOutPut = '';   
        this.originX = this.originX + 1;
        this.originX = this.originX - 1;   
    },
    // get number of outputs connected to component
    get_number_of_outputs_connected_to_component(keyComp) {
        number  = 0;
        components  = this.currentCircuit.arch;
        Object.keys(components).forEach(function(key) {
            outputs = components[key].outputs;
            Object.keys(outputs).forEach(function(keyOut) {
                idOfConnection = outputs[keyOut].idOfConnection;
                if (idOfConnection == keyComp) {
                    number  = number + 1;
                }
            }.bind(this));
        
        }.bind(this));

        return number;
    },

    // connect component to current Clicked OutPut
    addConnection(keyComp){
        if (!this.currentClickedOutPut) {
            return
        }

        destinComponent  = this.currentCircuit.arch[keyComp];
        multiple_connection = destinComponent.multiple_connection;
        if (multiple_connection == 0) {
            number_of_connection = this.get_number_of_outputs_connected_to_component(keyComp);
            if (number_of_connection) {
                this.show_toast("you can't connect more than one output to this component", false); 
                return false;
            }
        }
        from  = this.currentCircuit.arch[this.currentClickedOutPut[0]].outputs[this.currentClickedOutPut[1]];
        fromCircuit  = this.currentCircuit.arch[this.currentClickedOutPut[0]];
        if (! from.args) {
            from.args = {};
        }

        if (! destinComponent.input) {
            destinComponent.input = {};
        }

        if (! destinComponent.outputs) {
            destinComponent.outputs = {};
        }

        auto_fill_of_args = destinComponent.auto_fill_of_args;
        if ( auto_fill_of_args == 1) {
            Object.keys(destinComponent.outputs).forEach(function(key) {
                if (! destinComponent.outputs[key].args) {
                    destinComponent.outputs[key].args = {};
                }
                destinComponent.outputs[key].args = JSON.parse(JSON.stringify(from.args));

                Object.keys(destinComponent.outputs[key].args).forEach(function(keyArg) {
                    delete destinComponent.outputs[key].args[keyArg]['exported'];
                }.bind(this));
                
            }.bind(this));
        }

        from.idOfConnection = keyComp;
        from.connection = destinComponent.trigger_of_component;

        from.args_options = {};
        inputs  = destinComponent.input;
        Object.keys(inputs).forEach(function(key) {
            if (inputs[key].select == '1') {
                from.args_options[key] = {
                    'type_of_map' : 'select',
                    'map' : '',
                };
            } else {
                from.args_options[key] = {
                    'type_of_map' : 'map',
                    'map' : '',
                 };
            }
                });

        Object.keys(from.args).forEach(function(key) {
            if (from.args[key].exported) {
                if (!destinComponent.exported) {
                    destinComponent.exported = {};
                }
                destinComponent.exported[key] = {
                    'key' : key,
                };
                exportedArg = JSON.parse(JSON.stringify(from.args[key]));

                // outputs Of Component Connected To Current Output
                outputs = destinComponent.outputs;

                Object.keys(outputs).forEach(function(keyout) {
                    if (outputs[keyout].args === undefined || outputs[keyout].args.length == 0) {
                        outputs[keyout].args = {};
                    }
                    k =  JSON.parse(JSON.stringify(from.args[key]));
                    k.exported = 0;
                    outputs[keyout].args["_exp_" + key] =  k;
                }.bind(this));
            }

        }.bind(this));       

        this.currentClickedOutPut = false;
        this.originX = this.originX + 1;
        this.originX = this.originX - 1;
    },

    zoomPlus() {    
        this.width = Math.floor(this.width * 0.9);
        this.height = Math.floor(this.height * 0.9);
        this.zoom = this.zoom * 0.9;
    },

    zoomMinus() {   
        this.width = Math.floor(this.width * 1.1);
        this.height = Math.floor(this.height * 1.1);
        this.zoom = this.zoom * 1.1;
    },

    DragSvg(e) {
        e.stopPropagation();
        
       if(e.buttons==1)
     {
        this.originX = this.originX - this.zoom * e.movementX;
        this.originY = this.originY - this.zoom * e.movementY;
     }
    },

    doDrag(key, e) {
        e.stopPropagation();
        component = this.currentCircuit.arch[key];
      
        const meedsvg = document.getElementById(key);
        if(e.buttons==1)
         {
            t   = meedsvg.transform.baseVal.getItem(0);
            tx  = t.matrix.e;
            ty  = t.matrix.f;

            component.x = component.x + tx + 1.5 *  this.zoom * e.movementX;
            component.y = component.y + ty  + 1.5 * this.zoom *  e.movementY;

            this.originX = this.originX + 1;
            this.originX = this.originX - 1;
         }
    }

    },
    mounted() {
        // zoom in svg
        for (var i = 1; i <= 12; i++) {
            this.zoomMinus();
        }

        var data = {
            'action' : 'fetch_ultrapress',
         };
        
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                this.components = data.components;
                this.triggers = data.triggers;
                this.circuits = data.circuits; 
                this.packages = data.packages; 

                // initialize circuits
                Object.keys(this.circuits).forEach(function(key) {
                   circuit = this.circuits[key];
                   Object.keys(circuit.arch).forEach(function(keycomp) {
                        comp = circuit.arch[keycomp];
                        Object.keys(comp.outputs).forEach(function(keyout) {
                            if (comp.outputs[keyout].blocked) {
                                if (  comp.outputs[keyout].blocked == '0') {
                                    comp.outputs[keyout].blocked = 0; 
                                } else {
                                    comp.outputs[keyout].blocked = 1;
                                }
                            }
                            }.bind(this));
                        }.bind(this));
                }.bind(this));

                this.data_loaded = true;
                this.currentCircuitKey = data.key_of_first_circuit;
            },
            error: (data,status,error) => {
            },
        });
    }

});

/** 
 * component: add-circuits-comps
* page of circuits
*/

var add_circuits_comps = Vue.component('add-circuits-comps', {
    template: '#add-circuits-comps',  // look in ultrapress-admin-display-add-circuits-comps.php
    data() {
        return {
            keys: [],
            'cats' : [],
        }
    },
    computed: {},
    methods: {
    
    load_circuits_comps_ajax(event){ 
        keys = event.target.value;
        
        var data = {
            'action' : 'load_ultrapress',
            'keys' : keys,
            'cats' : [],
        };

        $.ajax({
          type: "POST",
          url: "https://ultra-press.com/wp-admin/admin-ajax.php", 
          data: data,
          //dataType: 'jsonp',
          //dataType: 'json',
          success: (data,status,error) => {
            $('#inject-html').html(data); // insert data

                },
          error: (data,status,error) => {
            $('#inject-html').html('you need SSL certificat to load packages via Ajax.<br>you can download packages manually from <br> <a href="https://ultra-press.com/packages/" class="btn btn-outline-secondary mx-3">here</a> '); // insert data
                },
        })  
    },
    },
    mounted() {
    keys ='';
    var data = {
        'action' : 'load_ultrapress',
        'keys' : keys,
    };

    $.ajax({
      type: "POST",
      url: "https://ultra-press.com/wp-admin/admin-ajax.php", 
      data: data,
      //dataType: 'json',
      success: (data,status,error) => {
        $('#inject-html').html(data); // insert data
            },
      error: (data,status,error) => {
        $('#inject-html').html('<div> <p>An error occurred, packages could not be loaded via AJAX.</p>' 
        + '</br><p>you can download packages manually from <a href="https://ultra-press.com/packages/">here</a></p></div>'); // insert data
            },
    })  
    }
});


// end add-circuits-comps component

/** 
 * component: list-circuits
* page of circuits
*/

var list_of_circuits = Vue.component('list-circuits', {
    template: '#list-circuits',  // look in ultrapress-admin-display-circuits.php
    data() {
        return {
            triggers: [],
            newCircInfo: {},
            currentCompKey: '',
        }
    },
    computed: {},
    methods: {
    deactivate_circuit(key_of_circ){ 
        var $button = $('#deactivate-circuit');
        var data = {
            'action' : 'deactivate_circuit_ultrapress',
            'circuit_key' : key_of_circ,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        });     
    },

    activate_circuit(key_of_circ, json_path){ 
        var $button = $('#activate-circuit');
        var data = {
            'action' : 'activate_circuit_ultrapress',
            'circuit_key' : key_of_circ,
            'circuit_path' : json_path,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        });     
    },
    
    
    delete_circuit(key_of_circ, json_path){ 
        var $button = $('#delete-circuit');
        var data = {
            'action' : 'delete_circuit_ultrapress',
            'circuit_key' : key_of_circ,
            'circuit_path' : json_path,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        });     
    },

    },
    mounted() {}
});

// end list-circuits



/**
* component: list-components
* page of components
*/
var mmmm = Vue.component('list-components', {
    template: '#list-components',  // look in ultrapress-admin-display-components.php
    data() {
        return {
            triggers: [],
            newCircInfo: {},
            currentCompKey: '',
        }
    },
    computed: {},
    methods: {
    delete_component(key_of_comp){ 
        var $button = $('#delete-component');
        var data = {
            'action' : 'delete_component_ultrapress',
            'key_of_comp' : key_of_comp,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        });     
    },
    delete_composed_component(key_of_comp){ 
        var $button = $('#delete-composed-component');

        var data = {
            'action' : 'delete_composed_component_ultrapress',
            'key_of_comp' : key_of_comp,
            'ultrapress_nonce': $button.data('nonce'),
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                }
                $button.prop('disabled', false);
                location.reload();
            },
             error: (data,status,error) => {
                $button.prop('disabled', false);
                location.reload();
            },
        });     
    },
    },
    mounted() {}
});

// end list-components


/** 
 * component: list-packages
* page of circuits
*/

var list_of_packages = Vue.component('list-packages', {
    template: '#list-packages',  // look in ultrapress-admin-display-packages.php
    data() {
        return {
            triggers: [],
            newCircInfo: {},
            currentCompKey: '',
        }
    },
    computed: {},
    methods: {
    deactivate_package(name_of_package){ 
        var $button = $('#deactivate-package');
        var data = {
            'action' : 'deactivate_package_ultrapress',
            'name_of_package' : name_of_package,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        }); 
    },

    activate_package(event){
        const buttonValue = event.currentTarget.id;
        var $button = $('#' + buttonValue);
        name_of_package = $button.data('name_of_package') ;
        path_of_package = $button.data('path_of_package') ;
        var data = {
            'action' : 'activate_package_ultrapress',
            'name_of_package' : name_of_package,
            'path_of_package' : path_of_package,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        });  
    },
    
    delete_package(event){ 
        const buttonValue = event.currentTarget.id;
        var $button = $('#' + buttonValue);
        name_of_package = $button.data('name_of_package') ;
        path_of_package = $button.data('path_of_package') ;
        var data = {
            'action' : 'delete_package_ultrapress',
            'name_of_package' : name_of_package,
            'path_of_package' : path_of_package,
            'ultrapress_nonce': $button.data('nonce'), 
         };

        $button.prop('disabled', true);
        $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        });     
    },     

    },
    mounted() {}
});

// end list-packages




new Vue({
    el: "#app",
    data: {
    },

});
new Vue({
    el: "#app3",
    data: {
    },

});

new Vue({
  el: '#app1',
  data: {
    variableAtParent: 'DATA FROM PARENT!',
    xx: '30',
  }
})




// end ultrapress-dashborad component



/**
 * initialise all dialog windows
 */

$( "#dialog" ).dialog({
      autoOpen: false,
      height: 300,
      width: 600,
      modal: true,
      title: "run",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-update-package" ).dialog({
      autoOpen: false,
      height: 300,
      width: 600,
      modal: true,
      title: "update package",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });


$( "#dialog-new-circuit" ).dialog({
      autoOpen: false,
       height: 550,
      maxHeight: 1000,
      width: 600,
      modal: true,
      title: "create new circuit",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
$( "#dialog-new-arg-node" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
$( "#dialog-edit-arg" ).dialog({
      autoOpen: false,
      height: 400,
      maxHeight: 1000,
      width: 800,
      modal: true,
      title: "edit args",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-edit-activate-arg" ).dialog({
      autoOpen: false,
      height: 600,
      maxHeight: 1000,
      width: 800,
      modal: true,
      title: "edit activate arg",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
$( "#dialog-edit-deactivate-arg" ).dialog({
      autoOpen: false,
      height: 600,
      maxHeight: 1000,
      width: 800,
      modal: true,
      title: "edit deactivate arg",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-edit-trig" ).dialog({
      autoOpen: false,
      height: 600,
      maxHeight: 1000,
      width: 800,
      modal: true,
      title: "edit trigger",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
$( "#dialog-delete-component" ).dialog({
      autoOpen: false,
      modal: true,
      title: "delete component",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-plugin" ).dialog({
      autoOpen: false,
      height: 500,
      maxHeight: 1000,
      width: 600,
      modal: true,
      title: "create plugin",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-package" ).dialog({
      autoOpen: false,
      height: 500,
      maxHeight: 1000,
      width: 600,
      modal: true,
      title: "create package",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-export-composed-comp" ).dialog({
      autoOpen: false,
      height: 500,
      maxHeight: 1000,
      width: 600,
      modal: true,
      title: "expore composed component",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-explore-comp" ).dialog({
      autoOpen: false,
      height: 600,
      maxHeight: 1000,
      width: 1000,
      modal: true,
      title: "explore composed component",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-new-trigger" ).dialog({
      autoOpen: false,
      height: 300,
      width: 600,
      modal: true,
      title: "choose trigger",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-new-activate" ).dialog({
      autoOpen: false,
      height: 300,
      width: 600,
      modal: true,
      title: "new activate",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
$( "#dialog-new-deactivate" ).dialog({
      autoOpen: false,
      height: 300,
      width: 600,
      modal: true,
      title: "new deactivate",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

$( "#dialog-run" ).dialog({
      autoOpen: false,
      height: 500,
      maxHeight: 1000,
      width: 600,
      modal: true,
      title: "run",
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

/**
 * click event for buttons loaded dynamically
**/
$('#inject-html').on('click', 'button', function(){
    var $button = $( this );
    link = $(this).data('link')
    var data = {
            'action' : 'install_circuit_component',
            'link' : link,
        };

    $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: data,
            dataType: 'json',
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                }  
                location.reload();
            },
            error: (data,status,error) => {
                location.reload();
            },
        });

});

/**
 * upload package
**/
$('#Upload-package-form').on('click', 'button', function(){
    var $button = $( this );
    var formData = new FormData();
 	var files = $('#file')[0].files[0];

    formData.append('file',files);
    formData.append("action", "ultrapress_upload_package");
    formData.append("ultrapress_nonce", $(this).data('nonce') );

    $.ajax({
            type: "POST",
            url: mysettings.ajaxurl,
            data: formData,
            dataType: 'json',
	    	cache: false,
	    	processData: false, // Don't process the files
	        contentType: false,
            success: (data,status,error) => {
                if (data.response == "F") {
                  console.log(data.error_message);    
                } 
                console.log(data); 
                //location.reload();
            },
            error: (data,status,error) => {
            	console.log(data); 
                //location.reload();
            },
        });

});


////////////////////



});

})( jQuery );

class TipsCarousel {
    constructor() {
        console.log('TipsCarousel: Constructor called');
        this.currentSlide = 0;
        this.container = document.getElementById('tips-carousel-container');
        this.slides = this.container ? this.container.querySelectorAll('.tip-slide') : [];
        this.prevBtn = this.container ? this.container.querySelector('.tip-nav.prev') : null;
        this.nextBtn = this.container ? this.container.querySelector('.tip-nav.next') : null;
        
        console.log('TipsCarousel: Elements found:', {
            container: this.container,
            slides: this.slides.length,
            prevBtn: this.prevBtn,
            nextBtn: this.nextBtn
        });

        if (this.container && this.slides.length > 0) {
            this.init();
        }
    }

    init() {
        console.log('TipsCarousel: Initializing');
        this.showSlide(0);
        
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prevSlide());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextSlide());
        }

        // Auto-advance slides
        setInterval(() => this.nextSlide(), 5000);
    }

    showSlide(index) {
        console.log('TipsCarousel: Showing slide', index);
        this.slides.forEach(slide => {
            slide.classList.remove('active');
        });
        
        this.currentSlide = index;
        this.slides[index].classList.add('active');
    }

    nextSlide() {
        console.log('TipsCarousel: Next slide');
        let next = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(next);
    }

    prevSlide() {
        console.log('TipsCarousel: Previous slide');
        let prev = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.showSlide(prev);
    }
}

// Initialize carousel when DOM is loaded
jQuery(document).ready(function($) {
    console.log('DOM Ready - Looking for carousel');
    const container = document.getElementById('tips-carousel-container');
    console.log('Carousel container:', container);
    
    if (container) {
        const slides = container.querySelectorAll('.tip-slide');
        const prevBtn = container.querySelector('.tip-nav.prev');
        const nextBtn = container.querySelector('.tip-nav.next');
        let currentSlide = 0;
        
        console.log('Found slides:', slides.length);
        
        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            slides[index].classList.add('active');
            currentSlide = index;
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }
        
        // Initialize first slide
        showSlide(0);
        
        // Add button handlers
        if (prevBtn) prevBtn.addEventListener('click', prevSlide);
        if (nextBtn) nextBtn.addEventListener('click', nextSlide);
        
        // Auto-advance
        setInterval(nextSlide, 5000);
    }
});