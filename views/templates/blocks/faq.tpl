<section class="featured-products clearfix mt-3">
{prettyblocks_title block=$block field="title" classes=['h2','products-section-title','text-uppercase']}
<div id="faqAccordion" class="accordion">
  {foreach from=$block.states item=faq key=key name="faqLoop"}
    <div class="card">
    <div class="card-header" id="heading{$smarty.foreach.faqLoop.iteration}">
    <h5 class="mb-0">
    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{$smarty.foreach.faqLoop.iteration}" aria-expanded="true" aria-controls="collapse{$smarty.foreach.faqLoop.iteration}">
           {prettyblocks_title index=$key block=$block field="hello" }
          </button>
        </h5>
      </div>

      <div id="collapse{$smarty.foreach.faqLoop.iteration}" class="collapse" aria-labelledby="heading{$smarty.foreach.faqLoop.iteration}" data-parent="#faqAccordion">
        <div class="card-body">
            {$faq.content nofilter}
        </div>
      </div>
    </div>
  {/foreach}
</div>

</section>