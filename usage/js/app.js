$(document).ready(function(){

    document.addEventListener('fieldAdded', function(e) {
        $('.className-wrap').hide();
    });

    var formBuilder = $('#formBuilder').formBuilder({
        disabledActionButtons: ['clear','save','data'],
        disableFields:[
            'button',
            'autocomplete',
            'file',
            'hidden'
        ],
        typeUserDisabledAttrs: {
            'checkbox-group':[
                // Have to leave className otherwise form-control class is removed. Hiding elements every time a new element
                // is added as a hack for this
                // 'className',
                'description',
                'toggle',
                'name',
                'access',
                'other'
            ],
            'date':[
                'description',
                //'className',
                'name',
                'access',
                'placeholder',
                'value'
            ],
            'header':[
                //'className',
                'access'
            ],
            'number':[
                //'className',
                'description',
                'placeholder',
                'name',
                'access',
                'value',
                'step'
            ],
            'paragraph':[
                'subtype',
                //'className',
                'access'
            ],
            'radio-group':[
                'description',
                //'className',
                'name',
                'access',
                'other'
            ],
            'select':[
                'description',
                'placeholder',
                //'className',
                'name',
                'access',
                'multiple'
            ],
            'text':[
                'description',
                'placeholder',
                //'className',
                'name',
                'access',
                'value',
                'subtype'
            ],
            'textarea':[
                'description',
                'placeholder',
                //'className',
                'name',
                'access',
                'value',
                'subtype'
            ]
        },
        // typeUserEvents:{
        //     'date':{
        //         onadd: function(fld){
        //             $('li .prev-holder .form-group input').addClass('example-class');
        //         }
        //     }
        // },
        actionButtons:[
            {
                id:'saveButton',
                className: 'btn btn-primary save-button',
                label:'Save',
                type:'button',
                events: {
                    click: function(){
                        // obtain json data of created form
                        console.log(formBuilder.actions.getData('json', true));
                    }
                }
            }
        ]
    });
});