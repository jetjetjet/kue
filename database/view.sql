CREATE OR REPLACE VIEW product_stock AS 
	SELECT
		productid,
		odshowcaseid as stockshowcaseid,
		SUM(COUNT)::decimal AS qty
	FROM
	(
		SELECT odproductid AS productid, 
			odshowcaseid,
			-odqty AS count 
		FROM orderdetail
		JOIN showcases s 
			ON s.id = odshowcaseid
		WHERE odactive = '1'
		AND odtype = 'PO'
		UNION ALL 
		SELECT showcaseproductid,
			id,
			showcaseqty 
		FROM showcases
		WHERE showcaseactive = '1'
		AND showcaseexpiredat IS NULL
	) a
	GROUP BY a.productid, a.odshowcaseid;
	