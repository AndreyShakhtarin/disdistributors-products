var $collectionHolder;
// setup an "add a tag" link
var $addTagLink = $('<span class="form-options"><a href="#" class="add_tag_link "> <i class="icon-plus black"></i> Add </a></span>');
var $newLinkLi = $('<span class="form-add"></span>').append($addTagLink);
var i = 0;
jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('td.tags');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);

        $collectionHolder.find().each(function() {
            addTagFormDeleteLink($(this));
        });
    });
    addTagForm($collectionHolder, $newLinkLi);

});
function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    //document.write($collectionHolder);
    var prototype = $collectionHolder.data('prototype');
    //document.write(prototype+"<br>");
    // get the new index
    var index = $collectionHolder.data('index');
    //document.write(index+"<br>");
    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var j = 0;
    var newForm = prototype.replace(/[0]/g, index);
    //document.write(newForm+"<br>");
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<tr class="form-added"></tr>').append(newForm);
    //document.write($newFormLi+"<br>");
    $newLinkLi.before($newFormLi);
    if(i !=0){
        addTagFormDeleteLink($newFormLi);
    }
    ++i;
}


function addTagFormDeleteLink($tagFormLi) {

    var $removeFormA = $('<span class="form-options"><a href="#" class="deleted"> Delete </a></span>');


    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}


