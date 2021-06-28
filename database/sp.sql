create or replace function report_transaction
(
	p_startdate date,
  p_enddate date,
  p_in varchar,
  p_out int
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
      --and ordervoidedat is null
      and ordercreatedat between p_startdate::date and p_enddate::date
      and case when p_in is not null then orderstatus = upper(p_in) 
      else orderstatus not in ('DRAFT', 'VOIDED') end
      union all
      select 
        o2.id, 
        'Pemasukan'::varchar ,
        orderinvoice, 
        ordercustname,
        (
        	select string_agg(odtype, ' & ') from  ( select odtype from orderdetail o3 where o3.odorderid = o2.id and odactive = '1' group by odtype)sb 
        )::varchar,
        coalesce(ordercompleteddate, ordercreatedat)::date,
        coalesce(orderremainingpaid, orderprice), 
        null,
        orderstatus::varchar
      from orders o2
      where orderactive = '1'
      and ordercreatedat between p_startdate::date and p_enddate::date
      and case when p_in is not null then orderstatus = upper(p_in) 
        else orderstatus not in ('DRAFT', 'VOIDED', 'DP') end
      and case when upper(p_in) = 'DP' then false else true end
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
      and 1 = p_out
    ) a
    order by a.orderdate;
end;
$$ language plpgsql;

create or replace function report_sumtransaction
(
	p_startdate date,
  p_enddate date,
  p_in varchar,
  p_out int
)
returns table(
  total_debit numeric,
  total_kredit numeric, 
  sub_total numeric
) as $$
declare
	v_debit numeric;
	v_kredit numeric;
begin
  if(p_in is not null and upper(p_in) = 'DP') then
    select sum(orderdp)
    from orders o2
    where orderactive = '1'
    --and ordervoidedat is null
    and ordercreatedat between p_startdate::date and p_enddate::date
    and case when p_in is not null then orderstatus = upper(p_in) else orderstatus not in ('DRAFT', 'VOIDED') end
    into	v_debit;
  else
    select sum(orderprice)
    from orders o2
    where orderactive = '1'
    --and ordervoidedat is null
    and ordercreatedat between p_startdate::date and p_enddate::date
    and case when p_in is not null then orderstatus = upper(p_in) else orderstatus not in ('DRAFT', 'VOIDED') end
    into	v_debit;
  end if;
    
  select sum(expenseprice)
  from expenses e 
  where expenseactive = '1'
  and expenseexecutedat between p_startdate::date and p_enddate::date
  and 1 = p_out
  into	v_kredit;
    
  return query select coalesce(v_debit,0)::numeric, coalesce(v_kredit, 0)::numeric, coalesce(v_debit,0)::numeric - coalesce(v_kredit, 0)::numeric;
end;
$$ language plpgsql;