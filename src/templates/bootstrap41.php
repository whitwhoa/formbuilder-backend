
<input type="hidden" name="formId" value="<?php echo $this->formId;?>"/>
<?php foreach($this->formData as $e): ?>

    <?php if($e->type === 'header'): ?>


        <<?php echo $e->subtype; ?>><?php echo $e->label; ?></<?php echo $e->subtype;?>>


    <?php elseif($e->type === 'paragraph'): ?>


        <p><?php echo $e->label; ?></p>


    <?php elseif($e->type === 'text'): ?>


        <div class="form-group">
            <label for="<?php echo $e->name; ?>" class="control-label"><?php echo $e->label; ?></label>
            <input id="<?php echo $e->name; ?>" type="text" class="form-control<?php echo $this->errors[$e->name] ? ' is-invalid' : '' ?>"
                   name="<?php echo $e->name; ?>" value="<?php echo isset($this->old[$e->name]) ? $this->old[$e->name] : '' ?>">
            <?php if(isset($this->errors[$e->name])): ?>
            <div class="invalid-feedback">
                <strong><?php echo $this->errors[$e->name]; ?></strong>
            </div>
            <?php endif; ?>
        </div>


    <?php elseif($e->type === 'select'): ?>


        <div class="form-group">
            <label for="sport" class="control-label"><?php echo $e->label; ?></label>
            <select class="form-control<?php echo $this->errors[$e->name] ? ' is-invalid' : '' ?>"
                    id="<?php echo $e->name; ?>" name="<?php echo $e->name; ?>">
                <option value="">-- Select Option --</option>
                <?php foreach($e->values as $v): ?>
                    <option <?php echo isset($this->old[$e->name]) ? ((isset($v->value) && $v->value != '' && $v->value == $this->old[$e->name]) ? 'selected' :
                        ($this->old[$e->name] === $v->label ? 'selected' : '')) : '' ?>
                        value="<?php echo (isset($v->value) && $v->value != '') ? $v->value : $v->label ?>"><?php echo $v->label; ?></option>
                <?php endforeach; ?>
            </select>
            <?php if(isset($this->errors[$e->name])): ?>
                <div class="invalid-feedback">
                    <strong><?php echo $this->errors[$e->name]; ?></strong>
                </div>
            <?php endif; ?>
        </div>


    <?php elseif($e->type === 'radio-group'): ?>

        <div class="form-group">
            <?php if(property_exists($e, 'label')): ?>
                <label class="d-block"><?php echo $e->label; ?></label>
            <?php endif; ?>
            <?php foreach($e->values as $k => $v): ?>
                <div class="form-check <?php echo property_exists($e, 'inline') ? 'form-check-inline' : ''?>">
                    <input class="form-check-input" type="radio" name="<?php echo $e->name; ?>" id="<?php echo $e->name . '_' . $k; ?>"
                           value="<?php echo (isset($v->value) && $v->value != '') ? $v->value : $v->label ?>"
                    <?php echo isset($this->old[$e->name]) ? ((isset($v->value) && $v->value != '' && $v->value == $this->old[$e->name]) ? 'checked' :
                        ($this->old[$e->name] === $v->label ? 'checked' : '')) : '' ?>>
                    <label class="form-check-label" for="<?php echo $e->name . '_' . $k; ?>"><?php echo $v->label; ?></label>
                </div>
            <?php endforeach; ?>
        </div>

    <?php elseif($e->type === 'number'): ?>

        <div class="form-group" style="width:180px;">
            <label for="<?php echo $e->name; ?>" class="control-label"><?php echo $e->label; ?></label>
            <input id="<?php echo $e->name; ?>" type="number" class="form-control<?php echo $this->errors[$e->name] ? ' is-invalid' : '' ?>"
                   name="<?php echo $e->name; ?>" value="<?php echo isset($this->old[$e->name]) ? $this->old[$e->name] : '' ?>"
                    <?php if(property_exists($e, 'min')): ?>min="<?php echo $e->min; ?>"<?php endif; ?>
                    <?php if(property_exists($e, 'max')): ?>max="<?php echo $e->max; ?>"<?php endif; ?>>
            <?php if(isset($this->errors[$e->name])): ?>
                <div class="invalid-feedback">
                    <strong><?php echo $this->errors[$e->name]; ?></strong>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif($e->type === 'date'): ?>

        <div class="form-group" style="width:180px;">
            <label for="<?php echo $e->name; ?>" class="control-label"><?php echo $e->label; ?></label>
            <input id="<?php echo $e->name; ?>" type="date" class="form-control<?php echo $this->errors[$e->name] ? ' is-invalid' : '' ?>"
                   name="<?php echo $e->name; ?>" value="<?php echo isset($this->old[$e->name]) ? $this->old[$e->name] : '' ?>">
            <?php if(isset($this->errors[$e->name])): ?>
                <div class="invalid-feedback">
                    <strong><?php echo $this->errors[$e->name]; ?></strong>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif($e->type === 'textarea'): ?>

        <div class="form-group">
            <label for="<?php echo $e->name; ?>" class="control-label"><?php echo $e->label; ?></label>
            <textarea class="form-control<?php echo $this->errors[$e->name] ? ' is-invalid' : '' ?>"
            id="<?php echo $e->name; ?>" name="<?php echo $e->name; ?>"
            rows="<?php echo $e->rows;?>"><?php echo isset($this->old[$e->name]) ? $this->old[$e->name] : '' ?></textarea>
            <?php if(isset($this->errors[$e->name])): ?>
                <div class="invalid-feedback">
                    <strong><?php echo $this->errors[$e->name]; ?></strong>
                </div>
            <?php endif; ?>
        </div>

    <?php elseif($e->type === 'checkbox-group'): ?>

        <div class="form-group">
            <?php if(property_exists($e, 'label')): ?>
                <label class="d-block"><?php echo $e->label; ?></label>
            <?php endif; ?>
            <?php foreach($e->values as $k => $v): ?>
                <div class="form-check <?php echo property_exists($e, 'inline') ? 'form-check-inline' : ''?>">
                    <input class="form-check-input<?php echo ($this->errors[$e->name . '.' . $k] || $this->errors[$e->name]) ? ' is-invalid' : '' ?>"
                           type="checkbox" name="<?php echo $e->name; ?>[]" id="<?php echo $e->name . '_' . $k; ?>"
                           value="<?php echo (isset($v->value) && $v->value != '') ? $v->value : $v->label ?>"
                        <?php echo isset($this->old[$e->name]) ? ((isset($v->value) && $v->value != '' && in_array($v->value, $this->old[$e->name])) ? 'checked' :
                            (in_array($v->label, $this->old[$e->name]) ? 'checked' : '')) : '' ?>>
                    <label class="form-check-label" for="<?php echo $e->name . '_' . $k; ?>"><?php echo $v->label; ?></label>
                </div>
            <?php endforeach; ?>
            <?php if(isset($this->errors[$e->name])): ?>
                <div class="invalid-feedback" style="display:block;">
                    <strong><?php echo $this->errors[$e->name]; ?></strong>
                </div>
            <?php endif; ?>
        </div>

    <?php endif; ?>


<?php endforeach; ?>




