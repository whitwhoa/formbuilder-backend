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
        replaceFields: [
            {
                type:"select",
                label: "Select",
                values:[
                    {label:'', value:''},
                    {label:'', value:''}
                ]
            },
            {
                type:"radio-group",
                label: "Radio Group",
                values:[
                    {label:'', value:''},
                    {label:'', value:''}
                ]
            },
            {
                type:"checkbox-group",
                label: "Checkbox Group",
                values:[
                    {label:'', value:''}
                ]
            }
        ],
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
                        // obtain json data of created form (and remove className property)
                        var data = JSON.parse(formBuilder.actions.getData('json'));
                        data.forEach(function(val, index){
                            delete data[index].className;
                        });
                        data = JSON.stringify(data);
                        console.log(data);
                        postToServer(data);
                        //postToServer(formBuilder.actions.getData('json'));
                    }
                }
            }
        ]
    });
});

function postToServer(d) {
    var $emd = $('#errorMessageDiv');
    var $smd = $('#successMessageDiv');
    $.ajax({
        url: '/create-form.php',
        type: 'POST',
        dataType: 'JSON',
        data: {
            fields: d
        },
        success:function(r){
            $smd.show();
            $emd.hide();
            setTimeout(function(){
                location.href = 'view-form.php?f='+r.responseJSON.formId;
            }, 1000);
        },
        error:function(r){
            if(r.status === 422){
                console.log(r.responseJSON.errors);
                $smd.hide();
                $emd.html((function(){
                    var s = '<ul>';
                    r.responseJSON.errors.forEach(function(v){
                        s += '<li>' + v + '</li>';
                    });
                    s += '</ul>';
                    return s;
                })());
                $emd.show();
            }
        }
    });
}