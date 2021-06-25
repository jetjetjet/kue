create or replace VIEW product_stock AS 
	select * from
	( 
		select
			productid,
			odshowcaseid as stockshowcaseid,
			SUM(counts)::decimal AS qty
		FROM
		(
			SELECT odproductid AS productid, 
				odshowcaseid,
				-odqty AS counts 
			FROM orderdetail od
			join orders o  
				on o.id = od.odorderid 
			JOIN showcases s 
				ON s.id = odshowcaseid
			WHERE odactive = '1'
			AND orderactive = '1'
			AND orderpaid = '1'
			AND odtype = 'READYSTOCK'
			UNION ALL 
			SELECT showcaseproductid,
				id,
				showcaseqty 
			FROM showcases
			WHERE showcaseactive = '1'
			AND showcaseexpiredat IS NULL
		) a
		GROUP BY a.productid, a.odshowcaseid
	) rt
	where rt.qty > 0;