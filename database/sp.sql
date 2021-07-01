create or replace function report_transaction_OLD
(
	p_startdate timestamp,
  p_enddate timestamp,
  p_in varchar,
  p_out int
)
returns table(
  id bigint, 
  trxtype varchar, 
  trxcode varchar, 
  customername varchar, 
  trxname varchar,
  trxdate timestamp, 
  debit numeric,
  discount numeric,
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
        orderdate::timestamp as orderdate, 
        orderdp,
        null,
        null::numeric,
        (case when orderstatus = 'VOIDED' then 'DP (Refund)' else 'DP' end)::varchar
      from orders o 
      where orderactive = '1'
      and orderdp is not null
      --and ordervoidedat is null
      and orderdate between p_startdate and p_enddate
      and case when p_in is not null then orderstatus = upper(p_in) 
      else orderstatus not in ('DRAFT') end
      union all
      select 
        o2.id, 
        'Pemasukan'::varchar ,
        orderinvoice, 
        ordercustname,
        (
        	select string_agg(odtype, ' & ') from  ( select odtype from orderdetail o3 where o3.odorderid = o2.id and odactive = '1' group by odtype)sb 
        )::varchar,
       -- coalesce(ordercompleteddate, orderdate)::timestamp,
        (case when orderstatus = 'DRAFT' then orderdate
          when orderstatus = 'DP' then orderdate
          when orderstatus = 'PAID' then orderpaidat
          when orderstatus = 'COMPLETED' then ordercompleteddate
          when orderstatus = 'VOIDED' then ordervoidedat end)::timestamp,
        case when orderremainingpaid is not null then orderremainingpaid + coalesce(orderdiscountprice, 0)
          else orderprice end,
        orderdiscountprice,
        null,
        (case when orderstatus = 'DRAFT' then 'Draf'
          when orderstatus = 'DP' then 'Bayar Dimuka'
          when orderstatus = 'PAID' then 'Lunas'
          when orderstatus = 'COMPLETED' then 'Selesai'
          when orderstatus = 'VOIDED' then 'Batal' end)::varchar as orderstatus
      from orders o2
      where orderactive = '1'
      --and case when p_in is not null then orderdate between p_startdate and p_enddate
      and case when p_in is not null and upper(p_in) = 'DRAFT' then orderdate between p_startdate and p_enddate
        when p_in is not null and upper(p_in) = 'PAID' then orderpaidat between p_startdate and p_enddate
        when p_in is not null and upper(p_in) = 'COMPLETED' then ordercompleteddate between p_startdate and p_enddate
        when p_in is not null and upper(p_in) = 'VOIDED' then ordervoidedat between p_startdate and p_enddate
        else ordercompleteddate between p_startdate and p_enddate end
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
        e.expensedate::timestamp,
        null,
        null,
        expenseprice,
        'Selesai'::varchar
      from expenses e 
      where expenseactive = '1'
      and expenseexecutedat between p_startdate and p_enddate
      and 1 = p_out
    ) a
    order by a.orderdate;
end;
$$ language plpgsql;

create or replace function report_transaction
(
	p_startdate timestamp,
  p_enddate timestamp,
  p_in varchar,
  p_out int
)
returns table(
  id bigint, 
  trxtype varchar, 
  trxcode varchar, 
  customername varchar, 
  trxname varchar,
  trxdate timestamp, 
  debit numeric,
  discount numeric,
  kredit numeric, 
  trxstatus varchar
) as $$
begin
	return query
    select * from 
    ( 
      select 
      o2.id as orderid, 
      'Pemasukan'::varchar ,
      orderinvoice, 
      ordercustname,
      (
        select string_agg(odtype, ' & ') from  ( select odtype from orderdetail o3 where o3.odorderid = o2.id and odactive = '1' group by odtype)sb 
      )::varchar,
      subdate::timestamp as orderdate,
      subprice,
      case when substatus = 'DP' then null else orderdiscountprice end,
      null,
      label::varchar
      from orders o2
      join 
        ( select o1.id, orderdate as subdate, orderprice as subprice, 'DRAFT' as substatus, 'Draf' as label
          from orders o1
          where ordervoid is null
          and orderactive = '1'
          and orderstatus = 'DRAFT'
          and orderdate between p_startdate and p_enddate
          union all 
          select odp.id, orderdate, orderdp, 'DP', case when orderstatus = 'VOIDED' then 'DP (Refund)' else 'DP' end
          from orders odp
          where orderdp is not null
          and orderactive = '1'
          and orderdate between p_startdate and p_enddate
          union all 
          select ol.id, orderpaidat, orderprice , 'PAID', 'Lunas'
          from orders ol
          where ordervoid is null 
          and orderactive = '1'
          and orderstatus = 'PAID'
          and ordercompleteddate is null
          and orderpaidat between p_startdate and p_enddate
          union all
          select oc.id, ordercompleteddate ,
            case when orderremainingpaid is not null then orderremainingpaid + coalesce(orderdiscountprice, 0)
                else orderprice end,
            'COMPLETED', 'Selesai'
          from orders oc
          where ordervoid is null
          and orderactive = '1'
          and orderstatus = 'COMPLETED'
          and ordercompleteddate between p_startdate and p_enddate
          union all
          select ov.id, 
            ordervoidedat, 
            case when orderremainingpaid is not null then orderremainingpaid + coalesce(orderdiscountprice, 0)
                else orderprice end,
            'VOIDED', 'Batal'
          from orders ov
          where ordervoid is not null 
          and ordervoidedat between p_startdate and p_enddate
        ) a
        on a.id = o2.id
      where 1=1
      and case when p_in is null then substatus not in ('DRAFT', 'VOIDED')
       else substatus = upper(p_in) end

      union all

      select 
          e.id,
          'Pengeluaran'::varchar,
          e.expensecode,
          '-'::varchar,
          e.expensename,
          e.expensedate::timestamp,
          null,
          null,
          expenseprice,
          'Selesai'::varchar
      from expenses e 
      where expenseactive = '1'
      and expenseexecutedat between p_startdate and p_enddate
      and 1 = p_out
    ) aa
    order by aa.orderdate;
end;
$$ language plpgsql;

create or replace function report_sumtransaction
(
	p_startdate timestamp,
  p_enddate timestamp,
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
    and orderdate between p_startdate and p_enddate
    and case when p_in is not null then orderstatus = upper(p_in) else orderstatus not in ('DRAFT', 'VOIDED') end
    into	v_debit;
  else
    select sum(orderprice)
    from orders o2
    where orderactive = '1'
    --and ordervoidedat is null
    and orderdate between p_startdate and p_enddate
    and case when p_in is not null then orderstatus = upper(p_in) else orderstatus not in ('DRAFT', 'VOIDED') end
    into	v_debit;
  end if;
    
  select sum(expenseprice)
  from expenses e 
  where expenseactive = '1'
  and expenseexecutedat between p_startdate and p_enddate
  and 1 = p_out
  into	v_kredit;
    
  return query select coalesce(v_debit,0)::numeric, coalesce(v_kredit, 0)::numeric, coalesce(v_debit,0)::numeric - coalesce(v_kredit, 0)::numeric;
end;
$$ language plpgsql;