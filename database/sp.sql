create or replace function report_transaction
(
	p_startdate date,
  p_enddate date,
  p_in varchar
)
returns table(
  id bigint, 
  trxtype varchar, 
  trxcode varchar, 
  customername varchar, 
  trxname varchar,
  trxdate date, 
  debit numeric,
  kredit numeric, 
  trxstatus varchar
) as $$
begin
	return query
    select * from 
    ( select o.id as orderid, 
        'Pemasukan'::varchar ,
        orderinvoice, 
        ordercustname, 
        'DP'::varchar ,
        ordercreatedat::date as orderdate, 
        orderdp, 
        null::numeric,
        'DP'::varchar 
      from orders o 
      where orderactive = '1'
      and orderdp is not null
      and ordercreatedat between p_startdate::date and p_enddate::date
      and case when p_in is not null then orderstatus = upper(p_in) else true end
      union all
      select 
        o2.id, 
        'Pemasukan'::varchar ,
        orderinvoice, 
        ordercustname,
        (
        	select string_agg(odtype, ' & ') from  ( select odtype from orderdetail o3 where o3.odorderid = o2.id and odactive = '1' group by odtype)sb 
        )::varchar,
        ordercompleteddate::date, 
        coalesce(orderremainingpaid, orderprice), 
        null,
        'Lunas'::varchar
      from orders o2
      where orderactive = '1'
      and ordercompleteddate between p_startdate::date and p_enddate::date
      and case when p_in is not null then orderstatus = upper(p_in) else true end
      union all
      select 
        e.id,
        'Pengeluaran'::varchar,
        e.expensecode,
        '-'::varchar,
        e.expensename,
        e.expensedate::date,
        null,
        expenseprice,
        'Selesai'::varchar
      from expenses e 
      where expenseactive = '1'
      and expenseexecutedat between p_startdate::date and p_enddate::date
    ) a
    order by a.orderdate;
end;
$$ language plpgsql;