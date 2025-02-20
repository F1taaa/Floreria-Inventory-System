<h3>Product Details</h3>
<br />
<div id="form-block1">
  <form method="POST" action="processes/process.product.php?action=updateproduct"> 
    <div id="form-block-half1">
      <label for="fname">Product Name</label>
      <input type="text" id="pname" class="input" name="pname" value="<?php echo $product->get_prod_name($id); ?>" placeholder="Product name">

      <input type="hidden" id="prodid" name="prodid" value="<?php echo $id; ?>" />
      <label for="ptype">Type</label>
      <select id="ptype" name="ptype">
        <?php
        if ($product->list_types() != false) {
          foreach ($product->list_types() as $value) {
            extract($value);
        ?>
            <option value="<?php echo $type_id; ?>" <?php if ($product->get_prod_type($id) == $type_id) {
                                                      echo "selected";
                                                    }; ?>><?php echo $type_name; ?></option>
        <?php
          }
        }
        ?>
      </select>
    </div>
    <div id="button-block1">
      <input type="submit" value="Save">
    </div>
  </form>
  <div id="button-block1">
    <form action="processes/process.product.php?action=deleteproduct" method="POST">
      <input type="hidden" name="prodid" value="<?php echo $id; ?>">
      <input type="submit" value="Delete Product">
  </div>
  </form>
</div>
