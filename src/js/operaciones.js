function sumar_compra(Detalle)
{
	patron=/^[0-9]+(,[0-9]+)*$/; 
	if(isNaN(parseFloat(Detalle.PRECIO_COMPRA.value*1)))
	  {
		alert("ESTE CAMPO SOLO ACEPTA NUMEROS");
	  }
	  else
	  {
		var suma = parseFloat(Detalle.PRECIO_COMPRA.value*1)+parseFloat(Detalle.PRECIO_COMPRA.value*1)*10/100;
		Detalle.PRECIO_VENTA_CONTADO.value = Math.round(suma*100)/100;
		Detalle.PRECIO_VENTA_CREDITO.value = Math.round(suma*100)/100;
	}
}

function sumar_venta(Detalle)
{
	patron=/^[0-9]+(,[0-9]+)*$/; 
	if(isNaN(parseFloat(Detalle.PRODUCTO_PRECIO_COMPRA.value*1)))
	  {
		alert("ESTE CAMPO SOLO ACEPTA NUMEROS");
	  }
	  else
	  {
		var suma = parseFloat(Detalle.PRODUCTO_PRECIO_COMPRA.value*1)+parseFloat(Detalle.PRODUCTO_PRECIO_COMPRA.value*1)*10/100;
		Detalle.PRODUCTO_PRECIO_VENTA_CONTADO.value = Math.round(suma*100)/100;
		Detalle.PRODUCTO_PRECIO_VENTA_CREDITO.value = Math.round(suma*100)/100;
	}
}