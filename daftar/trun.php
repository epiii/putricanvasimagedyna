<?php $id = $_GET['id'];?>
<form method="post" action="bayar.php">
											
											<input type="hidden" value="Test Payment" name="item" />
											<input type="hidden" value="10000" name="rp" />
											<input type="hidden" value="10211507836106395_<?php echo $id;?>" name="tr" />
											<input type="hidden" value="6" name="sw" />
											<input type="hidden" value="10211507836106395" name="id" />
											<input type="hidden" value="new" name="tipe" />
											<input type="hidden" value="4000" name="ke" />
											<input type="submit" class="button fit special" value="Bayar" />
										</form>	